<?php

namespace app\controllers;

use app\components\Util;
use app\components\QueueManager;
use app\models\databaseObjects\Queue;
use app\controllers\_MainController;
use app\models\databaseObjects\Role;
use app\models\databaseObjects\User;
use app\models\databaseObjects\UserTokenCount;
use app\models\exceptions\common\CannotSaveException;
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

        $this->layout = 'blank';

        return $this->render('kiosk', [
            'roles' => $roleArray
        ]);
    }

    public function actionGenerate($id)
    {
        $this->response->format = Response::FORMAT_JSON;

        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        /** @var Role $role */
        $role = Role::find()->where(['id' => $id, 'is_open' => true, 'is_kiosk_visible' => true])->one();

        if (is_null($role)) throw new NotFoundHttpException('Unknown service');

        /** @var \app\models\databaseObjects\User[] $users */
        $users = $role->getUsers()->andWhere(['is_open' => true])->all();

        if (empty($users)) throw new NotFoundHttpException('All counters closed');

        $userIds = array_map(function ($user) {
            return $user->id;
        }, $users);

        /** @var UserTokenCount $userTokenCount */
        $userTokenCount = UserTokenCount::find()
            ->where(['user_id' => $userIds, 'date' => date('Y-m-d')])
            ->select(['*', 'MIN(`count` - `served`) AS `in_queue`'])
            ->orderBy(['in_queue' => SORT_ASC])
            ->limit(1)
            ->one();

        if (is_null($userTokenCount->id)) throw new NotFoundHttpException('All counters closed');

        $responseData = [];

        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $assignedUser = $users[array_search($userTokenCount->user_id, $userIds)];

            $userTokenCount->count += 1;

            if (!$userTokenCount->save()) throw new CannotSaveException($userTokenCount, 'Operation failed');

            $queue = new Queue();
            $queue->token = QueueManager::createToken($role->token_prefix, $userTokenCount->count);
            $queue->user_id = $assignedUser->id;
            $queue->date = date('Y-m-d');
            $queue->time = date('h:i:s');
            $queue->status = QueueManager::STATUS_CREATED;

            if (!$queue->save()) throw new CannotSaveException($queue, 'Operation failed');

            $responseData['token'] = $queue->token;
            $responseData['floor'] = $assignedUser->floor;
            $responseData['room'] = $assignedUser->room;
            $responseData['date'] = $queue->date;
            $responseData['time'] = $queue->time;

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
                'user_id' => \Yii::$app->user->identity,
                'date' => date('Y-m-d'),
                'status' => [
                    QueueManager::STATUS_CALLED,
                    QueueManager::STATUS_RECALLED,
                    QueueManager::STATUS_IN_PROGRESS
                ]
            ])->one();

        if (is_null($currentQueue)) {
            \Yii::$app->response->statusCode = 204;
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
                ['!=', 'id', \Yii::$app->user->identity->role_id],
                ['is_open' => true],
                ['is_kiosk_visible' => false]
            ])
            ->all();

        $rolesArray = [];

        foreach ($forwardRoles as $role) {
            $rolesArray[] = ['id' => $role->id, 'name' => $role->name];
        }

        $this->layout = 'blank';
        return $this->render('call', [
            'openCloseText' => \Yii::$app->user->identity->is_open ? 'Close' : 'Open',
            'forwardRoles' => $rolesArray
        ]);
    }

    public function actionCallNext()
    {
        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        $user = \Yii::$app->user->identity;

        /** @var Queue $previousQueue */
        $previousQueue = Queue::find()
            ->where([
                'user_id' => $user->id,
                'date' => date('Y-m-d'),
                'status' => [
                    QueueManager::STATUS_CALLED,
                    QueueManager::STATUS_RECALLED,
                    QueueManager::STATUS_IN_PROGRESS
                ]
            ])->one();

        if (!is_null($previousQueue)) {
            $previousQueue->status = QueueManager::STATUS_ENDED;
            $previousQueue->end_time = date('h:i:s');

            if (!$previousQueue->save()) throw new CannotSaveException($previousQueue, 'Failed');
        }

        /** @var Queue $queue */
        $queue = Queue::find()
            ->where([
                'user_id' => $user->id,
                'date' => date('Y-m-d'),
                'status' => QueueManager::STATUS_CREATED
            ])
            ->limit(1)
            ->one();

        if (is_null($queue)) {
            \Yii::$app->response->statusCode = 204;
            return $this->asJson([]);
        }

        /** @var UserTokenCount $userTokenCount */
        $userTokenCount = UserTokenCount::find()
            ->where(['user_id' => $user->id, 'date' => date('Y-m-d')])
            ->one();

        if (is_null($userTokenCount)) throw new ForbiddenHttpException('Counter closed');

        $transaction = \Yii::$app->db->beginTransaction();

        try {
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

        $user = \Yii::$app->user->identity;

        /** @var Queue $queue */
        $queue = Queue::find()
            ->where([
                'user_id' => $user->id,
                'date' => date('Y-m-d'),
                'status' => [QueueManager::STATUS_CALLED, QueueManager::STATUS_RECALLED]
            ])->one();

        if (is_null($queue)) {
            \Yii::$app->response->statusCode = 204;
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

        $currentUserId = \Yii::$app->user->identity->id;

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
        $users = $role->getUsers()->andWhere(['is_open' => true])->all();

        if (empty($users)) throw new NotFoundHttpException('All counters closed');

        $userIds = array_map(function ($user) {
            return $user->id;
        }, $users);

        /** @var UserTokenCount $userTokenCount */
        $userTokenCount = UserTokenCount::find()
            ->where(['user_id' => $userIds, 'date' => date('Y-m-d')])
            ->select(['*', 'MIN(`count` - `served`) AS `in_queue`'])
            ->orderBy(['in_queue' => SORT_ASC])
            ->limit(1)
            ->one();

        if (is_null($userTokenCount->id)) throw new NotFoundHttpException('All counters closed');

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $currentQueue->status = QueueManager::STATUS_ENDED;
            $currentQueue->end_time = date('h:i:s');

            if (!$currentQueue->save()) throw new CannotSaveException($currentQueue, 'Failed');

            $userTokenCount->count += 1;

            if (!$userTokenCount->save()) throw new CannotSaveException($userTokenCount, 'failed');

            $newQueue = new Queue();
            $newQueue->token = $token;
            $newQueue->user_id = $userTokenCount->user_id;
            $newQueue->date = date('Y-m-d');
            $newQueue->time = date('h:i:s');
            $newQueue->trail_id = $currentUserId;
            $newQueue->status = QueueManager::STATUS_CREATED;

            if (!$newQueue->save()) throw new CannotSaveException($newQueue, 'failed');

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    public function actionCallToken($token) {}

    public function actionOpenClose()
    {
        $this->response->format = Response::FORMAT_RAW;

        if (!Util::isFetchRequest()) throw new NotFoundHttpException();

        $user = \Yii::$app->user->identity;

        $userTokenCount = UserTokenCount::findOne(['user_id' => $user->id, 'date' => date('Y-m-d')]);

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $user->is_open = !$user->is_open;

            if (!$user->save()) throw new CannotSaveException($user, 'Failed');

            if (is_null($userTokenCount)) {
                $userTokenCount = new UserTokenCount();
                $userTokenCount->user_id = $user->id;
                $userTokenCount->count = 0;
                $userTokenCount->served = 0;
                $userTokenCount->date = date('Y-m-d');

                if (!$userTokenCount->save()) throw new CannotSaveException($userTokenCount, 'failed');
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

        return $user->is_open ? 'Close' : 'Open';
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

        $this->layout = 'blank';
        return $this->render('monitor', [
            'roles' => $roleArray
        ]);
    }

    public function actionMonitorSocket($ids, $lastLoadedId, $firstLoadedId)
    {
        $this->response->format = Response::FORMAT_JSON;

        $idArray = explode(',', $ids);

        $users = User::findAll(['role_id' => $idArray]);

        $userIds = [];

        $userIdToRoleId = [];

        foreach ($users as $user) {
            $userIdToRoleId[$user->id] = [
                'role' => $user->role_id,
                'floor' => $user->floor,
                'room' => $user->room,
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

        $ended = [];

        if ($firstLoadedId !== 0) {
            /** @var Queue[] $endedTokens */
            $endedTokens = Queue::find()
                ->where([
                    'and',
                    ['in', 'user_id', $userIds],
                    ['>', 'id', $firstLoadedId],
                    ['=', 'date', date('Y-m-d')],
                    ['=', 'status', QueueManager::STATUS_ENDED]
                ])
                ->all();
            
            foreach ($endedTokens as $endedToken) {
                $ended[] = [
                    'id' => $endedToken->id
                ];
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
            ];
        }

        return ['queue' => $queue, 'ended' => $ended];
    }
}
