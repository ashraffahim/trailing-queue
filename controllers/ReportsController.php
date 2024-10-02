<?php

namespace app\controllers;

use app\controllers\_MainController;
use app\models\databaseObjects\Queue;
use app\models\databaseObjects\User;
use app\models\databaseObjects\TokenCount;
use DateTime;
use DateTimeImmutable;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Reports Controller.
 */
class ReportsController extends _MainController
{
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionQueue() {
        $date = $this->request->get('date', date('Y-m-d'));
        $token = $this->request->get('token', '');

        $queue = Queue::find()
        ->where(['date' => $date]);
        
        if ($token !== '') {
            $queue->andWhere(['token' => $token]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $queue,
            
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
           
        ]);

        return $this->render('queue', [
            'queue' => $dataProvider,
            'date' => $date,
            'token' => $token,
        ]);
    }

    public function actionSummary() {        
        $oneMonthBack = (new DateTime())->modify('-1 month')->format('Y-m-d');
        
        $from = $this->request->get('from', $oneMonthBack);
        $to = $this->request->get('to', date('Y-m-d'));

        $queue = TokenCount::find()
        ->select(['utc.*'])
        ->alias('utc')
        ->where([
            'and',
            ['>=', 'date', $from],
            ['<=', 'date', $to]
        ])
        ->innerJoin(User::tableName() . ' AS `u`', '`utc`.`user_id` = `u`.`id`');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $queue,
            
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
           
        ]);

        return $this->render('summary', [
            'queue' => $dataProvider,
            'from' => $from,
            'to' => $to
        ]);
    }

    public function actionUserSummary($id, $date) {

        $user = User::findOne($id);

        if (is_null($user)) throw new NotFoundHttpException();

        /** @var Queue[] $models */
        $models = Queue::find()
        ->where([
            'user_id' => $id,
            'date' => $date
        ])
        ->all();

        $summary = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'date' => date('d M, Y', strtotime($date)),
            'count' => 0,
            'served' => 0,
            'wait_time' => 0,
            'serve_time' => 0,
            'recall_time' => 0,
            'recall_count' => 0,
        ];

        foreach ($models as $model) {
            $summary['count']++;

            $time = new DateTimeImmutable($model->time);

            if (!is_null($model->call_time)) {
                $summary['served']++;
                
                $call_time = new DateTimeImmutable($model->call_time);
                $call_time_interval = $time->diff($call_time);
                $summary['wait_time'] += $call_time_interval->i;
            }
            
            if (!is_null($model->end_time)) {
                $end_time = new DateTimeImmutable($model->end_time);
                $end_time_interval = $call_time->diff($end_time);
                $summary['serve_time'] += $end_time_interval->i;
            }
            
            if (!is_null($model->recall_time)) {
                $summary['recall_count'] += $model->recall_count;

                $recall_time = new DateTimeImmutable($model->recall_time);
                $recall_time_interval = $time->diff($recall_time);
                $summary['recall_time'] += $recall_time_interval->i;
            }
        }

        if ($summary['served'] > 0) {
            if ($summary['wait_time'] > 0) {
                $summary['wait_time'] = number_format($summary['wait_time'] / $summary['served'], 2);
            }
            if ($summary['serve_time'] > 0) {
                $summary['serve_time'] = number_format($summary['serve_time'] / $summary['served'], 2);
            }
            if ($summary['recall_time'] > 0) {
                $summary['recall_time'] = number_format($summary['recall_time'] / $summary['recall_count'], 2);
                $summary['recall_count'] = number_format($summary['recall_count'] / $summary['served'], 2);
            }
        }

        return $this->render('user-summary', ['summary' => $summary]);
    }
}
