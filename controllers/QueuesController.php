<?php

namespace app\controllers;

use app\components\Util;
use app\components\QueueManager;
use app\models\databaseObjects\Queue;
use app\controllers\_MainController;
use app\models\databaseObjects\Role;
use app\models\databaseObjects\RoleTokenCount;
use app\models\databaseObjects\User;
use app\models\databaseObjects\UserTokenCount;
use app\models\exceptions\common\CannotSaveException;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * QueuesController implements the CRUD actions for Queue model.
 */
class QueuesController extends _MainController
{
    public function actionKiosk()
    {
        $roles = Role::find()->where(['is_open' => true, 'is_kiosk_visible' => true])->all();

        $roleArray = [];

        foreach ($roles as $role) {
            $roleArray[] = ['id' => $role->id, 'name' => $role->name];
        }

        $this->layout = 'blank-dark';

        return $this->render('kiosk', [
            'roles' => $roleArray
        ]);
    }

    public function actionGenerate($id)
    {
        if (!Util::isFetchRequest()) throw new ForbiddenHttpException();

        $this->response->format = Response::FORMAT_JSON;

        /** @var Role $role */
        $role = Role::find()->where(['id' => $id, 'is_open' => true, 'is_kiosk_visible' => true])->one();

        if (is_null($role)) throw new NotFoundHttpException('Unknown service');

        $roleId = $role->id;
        $isPriority = false;

        if (!is_null($role->priority_for_id)) {
            $roleId = $role->priority_for_id;
            $isPriority = true;
        }

        /** @var User[] $users */
        $users = User::find()->where([
            'role_id' => $roleId,
            'is_active' => true
        ])->all();

        if (empty($users)) throw new NotFoundHttpException('No servers appointed');

        $today = date('Y-m-d');

        $roleTokenCounts = RoleTokenCount::find()->where(['role_id' => $roleId, 'date' => $today])->all();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $totalRegularTokenCount = 0;
            $totalPriorityTokenCount = 0;

            // Initialize token counter
            if (empty($roleTokenCounts)) {

                $roleTokenCount = $this->initializeRoleTokenCount($users, $today);
            } else {

                $roleTokenCount = $this->findMinTokenAssignedRoom($roleTokenCounts, $roleId, $today);

                foreach ($roleTokenCounts as $_roleTokenCount) {
                    $totalRegularTokenCount += $_roleTokenCount->count;
                    $totalPriorityTokenCount += $_roleTokenCount->priority_count;
                }
            }

            // Create token
            $queue = new Queue();
            $queue->role_id = $roleId;
            $queue->room_id = $roleTokenCount->room_id;
            $queue->date = $today;
            $queue->time = date('h:i:s');
            $queue->status = QueueManager::STATUS_CREATED;

            $currentToken = $this->getCurrentTokenOfRoom($roleId, $roleTokenCount->room_id, $today);

            // Token count increment
            $roleTokenCount->count += 1;

            if ($isPriority) {
                $queue->token = QueueManager::createToken($role->token_prefix, $totalPriorityTokenCount + 1);

                if (!is_null($currentToken))
                    $queue->priority_id = $this->getPriorityTokenShadowId($roleId, $currentToken->id, $today);

                $roleTokenCount->priority_count += 1;
            } else {
                $queue->token = QueueManager::createToken($role->token_prefix, $totalRegularTokenCount - $totalPriorityTokenCount + 1);
            }

            if (!$roleTokenCount->save()) throw new CannotSaveException($roleTokenCount);

            if (!$queue->save()) throw new CannotSaveException($queue);

            $responseData = [];

            $strtotime = strtotime($queue->date . ' ' . $queue->time);

            $responseData['token'] = $queue->token;
            $responseData['floor'] = $roleTokenCount->room->floor;
            $responseData['room'] = $roleTokenCount->room->name;
            $responseData['date'] = date('d M, Y', $strtotime);
            $responseData['time'] = date('h:i:s', $strtotime);
            $responseData['currentToken'] = $currentToken->token ?? null;

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $responseData;
    }

    public function actionCurrentToken()
    {

        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        /** @var Queue $previousQueue */
        $currentQueue = Queue::find()
            ->where([
                'user_id' => Yii::$app->user->identity->id,
                'date' => date('Y-m-d'),
                'status' => [
                    QueueManager::STATUS_CALLED,
                    QueueManager::STATUS_RECALLED,
                    QueueManager::STATUS_IN_PROGRESS
                ]
            ])->one();

        if (is_null($currentQueue)) {
            Yii::$app->response->statusCode = 204;
            return $this->asJson([]);
        }

        return $this->asJson([
            'token' => $currentQueue->token,
            'time' => $currentQueue->time,
        ]);
    }

    public function actionCall()
    {
        $forwardRoles = Role::find()
            ->where([
                'and',
                ['!=', 'id', Yii::$app->user->identity->role_id],
                ['is_open' => true],
                ['is_kiosk_visible' => false]
            ])
            ->all();

        $rolesArray = [];

        foreach ($forwardRoles as $role) {
            $rolesArray[] = ['id' => $role->id, 'name' => $role->name];
        }

        $this->layout = 'blank-blue-gradient';
        return $this->render('call', [
            'forwardRoles' => $rolesArray
        ]);
    }

    public function actionCallNext()
    {
        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        $user = Yii::$app->user->identity;

        $today = date('Y-m-d');

        /** @var UserTokenCount $userTokenCount */
        $userTokenCount = UserTokenCount::find()
            ->where(['user_id' => $user->id, 'date' => $today])
            ->one();

        if (is_null($userTokenCount)) throw new ForbiddenHttpException('Queue empty');

        /** @var Queue $previousQueue */
        $previousQueue = Queue::find()
            ->where([
                'user_id' => $user->id,
                'date' => $today,
                'status' => [
                    QueueManager::STATUS_CALLED,
                    QueueManager::STATUS_RECALLED,
                    QueueManager::STATUS_IN_PROGRESS
                ]
            ])->one();

        $regularQueue = Queue::find()
            ->where([
                'role_id' => $user->role_id,
                'room_id' => $user->room_id,
                'date' => $today,
                'status' => QueueManager::STATUS_CREATED
            ])
            ->limit(1)
            ->one();

        $queue = null;
        
        if (!is_null($regularQueue)) {
            $priorityQueue = Queue::find()
                ->where([
                    'and',
                    ['=', 'role_id', $user->role_id],
                    ['=', 'room_id', $user->room_id],
                    ['=', 'date', $today],
                    ['=', 'status', QueueManager::STATUS_CREATED],
                    ['<', 'priority_id', $regularQueue->id]
                ])
                ->limit(1)
                ->one();

            if (!is_null($priorityQueue))
                $queue = $priorityQueue;
            else
                $queue = $regularQueue;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // End previous token
            if (!is_null($previousQueue)) {
                $previousQueue->status = QueueManager::STATUS_ENDED;
                $previousQueue->end_time = date('h:i:s');

                if (!$previousQueue->save()) throw new CannotSaveException($previousQueue, 'Failed');
            }

            if (is_null($queue)) {
                $transaction->commit();

                Yii::$app->response->statusCode = 204;
                return $this->asJson([]);
            }

            $queue->user_id = $user->id;
            $queue->status = QueueManager::STATUS_CALLED;
            $queue->call_time = date('h:i:s');

            if (!$queue->save()) throw new CannotSaveException($queue, 'Failed');

            $userTokenCount->served += 1;
            $userTokenCount->last_id = $queue->id;

            if (!$userTokenCount->save()) throw new CannotSaveException($userTokenCount, 'Failed');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw $e;
        }

        return $this->asJson([
            'token' => $queue->token,
            'time' => $queue->time,
        ]);
    }

    public function actionRecall()
    {
        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        $user = Yii::$app->user->identity;

        /** @var Queue $queue */
        $queue = Queue::find()
            ->where([
                'user_id' => $user->id,
                'date' => date('Y-m-d'),
                'status' => [QueueManager::STATUS_CALLED, QueueManager::STATUS_RECALLED]
            ])->one();

        if (is_null($queue)) {
            Yii::$app->response->statusCode = 204;
            return $this->asJson([]);
        }

        $queue->status = QueueManager::STATUS_RECALLED;
        $queue->recall_count += 1;
        $queue->recall_time = date('h:i:s');

        if (!$queue->save()) throw new CannotSaveException($queue, 'Failed');
    }

    public function actionForward($rid, $token)
    {
        $this->response->format = Response::FORMAT_RAW;

        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        $currentUserId = (int) Yii::$app->user->identity->id;

        $currentQueue = Queue::findOne([
            'user_id' => $currentUserId,
            'token' => $token,
            'date' => date('Y-m-d'),
            'status' => [
                QueueManager::STATUS_CALLED,
                QueueManager::STATUS_RECALLED,
                QueueManager::STATUS_IN_PROGRESS,
                QueueManager::STATUS_ON_HOLD
            ]
        ]);

        if (is_null($currentQueue)) throw new NotFoundHttpException('Invalid token');

        /** @var Role $role */
        $role = Role::find()->where(['id' => $rid, 'is_open' => true])->one();

        if (is_null($role)) throw new NotFoundHttpException('Unknown service');

        /** @var \app\models\databaseObjects\User[] $users */
        $users = $role->users;
        // ->andWhere(['is_open' => true])

        if (empty($users)) throw new NotFoundHttpException('All counters closed');

        $userIds = array_map(function ($user) {
            return $user->id;
        }, $users);

        /** @var UserTokenCount $allUserTokenCount */
        $allUserTokenCount = UserTokenCount::find()
            ->where(['user_id' => $userIds, 'date' => date('Y-m-d')])
            ->select(['*', '(`count` - `served`) AS `in_queue`'])
            ->orderBy(['in_queue' => SORT_ASC])
            ->all();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            if (empty($allUserTokenCount)) {
                foreach ($userIds as $userId) {
                    $newUserTokenCount = new UserTokenCount();
                    $newUserTokenCount->user_id = $userId;
                    $newUserTokenCount->count = 0;
                    $newUserTokenCount->served = 0;
                    $newUserTokenCount->date = date('Y-m-d');

                    if (!$newUserTokenCount->save()) throw new CannotSaveException($newUserTokenCount, 'Failed');
                }

                $tokenCount = $newUserTokenCount;
            } else $tokenCount = $allUserTokenCount[0];

            $roleUserTokenCount = 1;

            foreach ($allUserTokenCount as $summingUserTokenCount) {
                $roleUserTokenCount += $summingUserTokenCount->count;
            }

            $currentQueue->status = QueueManager::STATUS_ENDED;
            $currentQueue->end_time = date('h:i:s');

            if (!$currentQueue->save()) throw new CannotSaveException($currentQueue, 'Failed');

            $tokenCount->count += 1;

            if (!$tokenCount->save()) throw new CannotSaveException($tokenCount, 'Failed');

            $newQueue = new Queue();
            $newQueue->token = $token;
            $newQueue->user_id = $tokenCount->user_id;
            $newQueue->date = date('Y-m-d');
            $newQueue->time = date('h:i:s');
            $newQueue->trail_id = $currentQueue->id;
            $newQueue->status = QueueManager::STATUS_CREATED;

            if (!$newQueue->save()) throw new CannotSaveException($newQueue, 'Failed');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    public function actionCallToken($token) {}

    public function actionNewTokenInQueue()
    {
        Yii::$app->log->targets[0]->enabled = false;

        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        $today = date('Y-m-d');

        $served = UserTokenCount::find()
            ->where([
                'role_id' => Yii::$app->user->identity->role_id,
                'room_id' => Yii::$app->user->identity->room_id,
                'date' => $today
            ])->sum('served');

        $count = RoleTokenCount::find()
            ->where([
                'role_id' => Yii::$app->user->identity->role_id,
                'room_id' => Yii::$app->user->identity->room_id,
                'date' => $today
            ])
            ->sum('count');

        $this->response->format = Response::FORMAT_RAW;

        if ($count - $served) return true;

        return false;
    }

    public function actionMonitor()
    {
        /** @var Role[] $roles */
        $roles = Role::find()->where(['is_open' => true])->all();

        $roleArray = [];

        foreach ($roles as $role) {
            $roleArray[] = [
                'id' => $role->id,
                'name' => $role->name,
            ];
        }

        $ads = glob(Yii::getAlias('@app/web/data/ads/*'));

        $adsArray = [];

        foreach ($ads as $ad) {
            $adsArray[] = '/data/ads/' . basename($ad);
        }

        $this->layout = 'blank-blue-gradient';
        return $this->render('monitor', [
            'roles' => $roleArray,
            'ads' => $adsArray
        ]);
    }

    public function actionMonitorSocket($lastLoadedId, $firstLoadedId)
    {
        Yii::$app->log->targets[0]->enabled = false;

        $this->response->format = Response::FORMAT_JSON;

        $users = User::find()->all();

        $userIds = [];

        $userIdToRoleId = [];

        foreach ($users as $user) {
            $userIdToRoleId[$user->id] = [
                'role' => $user->role_id,
                'floor' => $user->room->floor,
                'room' => $user->room->name,
            ];

            $userIds[] = $user->id;
        }

        /** @var Queue[] $tokens */
        $tokens = Queue::find()
            ->where([
                'and',
                ['in', 'user_id', $userIds],
                ['>', 'id', $lastLoadedId],
                ['=', 'date', date('Y-m-d')],
                [
                    'in',
                    'status',
                    [
                        QueueManager::STATUS_CREATED,
                        QueueManager::STATUS_CALLED,
                        QueueManager::STATUS_IN_PROGRESS,
                        QueueManager::STATUS_RECALLED,
                    ]
                ]
            ])
            ->all();

        $called = [];
        $recalled = [];
        $ended = [];

        if ($firstLoadedId !== 0) {
            /** @var Queue[] $updatedTokens */
            $updatedTokens = Queue::find()
                ->where([
                    'and',
                    ['in', 'user_id', $userIds],
                    ['>=', 'id', $firstLoadedId],
                    ['=', 'date', date('Y-m-d')],
                    [
                        'in',
                        'status',
                        [
                            QueueManager::STATUS_CALLED,
                            QueueManager::STATUS_RECALLED,
                            QueueManager::STATUS_ENDED,
                        ]
                    ]
                ])
                ->all();

            foreach ($updatedTokens as $updatedToken) {
                switch ($updatedToken->status) {
                    case QueueManager::STATUS_CALLED:
                        $called[] = [
                            'id' => $updatedToken->id,
                            'token' => $updatedToken->token,
                            'room' => $userIdToRoleId[$updatedToken->user_id]['room'],
                            'floor' => $userIdToRoleId[$updatedToken->user_id]['floor'],
                        ];
                        break;
                    case QueueManager::STATUS_RECALLED:
                        $recalled[] = [
                            'id' => $updatedToken->id,
                            'token' => $updatedToken->token,
                            'recall_count' => $updatedToken->recall_count,
                            'room' => $userIdToRoleId[$updatedToken->user_id]['room'],
                            'floor' => $userIdToRoleId[$updatedToken->user_id]['floor'],
                        ];
                        break;
                    case QueueManager::STATUS_ENDED:
                        $ended[] = ['id' => $updatedToken->id];
                        break;
                }
            }
        }

        $queue = [];

        foreach ($tokens as $token) {
            $queue[] = [
                'id' => $token->id,
                'token' => $token->token,
                'role_id' => $userIdToRoleId[$token->user_id]['role'],
                'floor' => $userIdToRoleId[$token->user_id]['floor'],
                'room' => $userIdToRoleId[$token->user_id]['room'],
                'date' => $token->date,
                'time' => $token->time,
                'status' => $token->status,
                'recall_count' => $token->recall_count,
            ];
        }

        return ['queue' => $queue, 'called' => $called, 'recalled' => $recalled, 'ended' => $ended];
    }

    private function initializeRoleTokenCount(array $users, string $date): RoleTokenCount
    {
        // Called within db transaction
        $initializedRooms = [];

        foreach ($users as $user) {
            $userTokenCount = new UserTokenCount();
            $userTokenCount->role_id = $user->role_id;
            $userTokenCount->room_id = $user->room_id;
            $userTokenCount->user_id = $user->id;
            $userTokenCount->served = 0;
            $userTokenCount->date = $date;

            if (!$userTokenCount->save()) throw new CannotSaveException($userTokenCount);

            if (in_array($user->room_id, $initializedRooms)) continue;

            $initializedRooms[] = $user->room_id;

            $roleTokenCount = new RoleTokenCount();
            $roleTokenCount->role_id = $user->role_id;
            $roleTokenCount->room_id = $user->room_id;
            $roleTokenCount->count = 0;
            $roleTokenCount->date = $date;

            if (!$roleTokenCount->save()) throw new CannotSaveException($roleTokenCount);
        }

        return $roleTokenCount;
    }

    private function findMinTokenAssignedRoom(array $roleTokenCounts, int $roleId, string $date): RoleTokenCount
    {
        $roomTokenPerUser = [];
        $totalRoomTokenCount = [];
        $totalUsersInRoom = [];

        $userTokenCounts = UserTokenCount::find()->where(['role_id' => $roleId, 'date' => $date])->all();

        foreach ($userTokenCounts as $userTokenCount) {
            if (!array_key_exists($userTokenCount->room_id, $totalRoomTokenCount)) $totalRoomTokenCount[$userTokenCount->room_id] = 0;
            $totalRoomTokenCount[$userTokenCount->room_id] -= $userTokenCount->served;

            if (!array_key_exists($userTokenCount->room_id, $totalUsersInRoom)) $totalUsersInRoom[$userTokenCount->room_id] = 0;
            $totalUsersInRoom[$userTokenCount->room_id] += 1;
        }

        foreach ($roleTokenCounts as $roleTokenCount) {
            $totalRoomTokenCount[$roleTokenCount->room_id] += $roleTokenCount->count;
        }

        foreach ($totalRoomTokenCount as $roomId => $roomTokenCount) {
            $roomTokenPerUser[$roomId] = $roomTokenCount / $totalUsersInRoom[$roomId];
        }

        $minTokenRoomId = array_search(min($roomTokenPerUser), $roomTokenPerUser);

        foreach ($roleTokenCounts as $roleTokenCount) {
            if ($minTokenRoomId === $roleTokenCount->room_id) return $roleTokenCount;
        }
    }

    private function getCurrentTokenOfRoom(int $roleId, int $roomId, string $date): ?Queue
    {
        $currentQueue = Queue::find()
            ->select(['id', 'token'])
            ->where([
                'role_id' => $roleId,
                'room_id' => $roomId,
                'date' => $date,
                'status' => [
                    QueueManager::STATUS_CALLED,
                    QueueManager::STATUS_RECALLED,
                    QueueManager::STATUS_IN_PROGRESS
                ]
            ])
            ->one();

        if (!is_null($currentQueue))
            return $currentQueue;

        $currentQueue = Queue::find()
            ->select(['id', 'token'])
            ->where([
                'role_id' => $roleId,
                'room_id' => $roomId,
                'date' => $date,
                'status' => QueueManager::STATUS_CREATED
            ])
            ->limit(1)
            ->one();

        return $currentQueue;
    }

    private function getPriorityTokenShadowId(int $roleId, int $currentTokenId, string $date): int
    {
        $priorityUncalledTokenCount = Queue::find()
            ->where(['not', ['priority_id' => null]])
            ->andWhere([
                'role_id' => $roleId,
                'date' => $date
            ])
            ->andWhere(['status' => QueueManager::STATUS_CREATED])
            ->count();

        return $currentTokenId + (2 * ($priorityUncalledTokenCount + 1));
    }
}
