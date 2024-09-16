<?php

namespace app\controllers;

use app\controllers\_MainController;
use app\models\databaseObjects\Queue;
use app\models\databaseObjects\User;
use app\models\databaseObjects\UserTokenCount;
use DateTime;
use yii\data\ActiveDataProvider;

/**
 * Reports Controller.
 */
class ReportsController extends _MainController
{
    public function actionIndex() {
        $oneMonthBack = (new DateTime())->modify('-1 month')->format('Y-m-d');
        
        $from = $this->request->get('from', $oneMonthBack);
        $to = $this->request->get('to', date('Y-m-d'));
        $token = $this->request->get('token');

        $queue = Queue::find()
        ->where([
            'and',
            ['>=', 'date', $from],
            ['<=', 'date', $to]
        ]);
        
        if (!is_null($token)) {
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

        return $this->render('index', [
            'queue' => $dataProvider,
            'from' => $from,
            'to' => $to,
            'token' => $token,
        ]);
    }

    public function actionSummary() {        
        $date = $this->request->get('date', date('Y-m-d'));

        $queue = UserTokenCount::find()
        ->select(['utc.*'])
        ->alias('utc')
        ->where(['date' => $date])
        ->leftJoin(User::tableName() . ' AS `u`', '`utc`.`user_id` = `u`.`id`');
        
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
            'date' => $date
        ]);
    }
}
