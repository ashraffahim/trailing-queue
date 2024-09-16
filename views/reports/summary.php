<?php

use app\models\databaseObjects\Queue;
use app\models\databaseObjects\User;
use app\models\databaseObjects\UserTokenCount;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $queue */

$this->title = 'Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-2xl">

        <?= GridView::widget([
            'dataProvider' => $queue,
            'tableOptions' => ['class' => 'table-classic'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'Name',
                    'value' => function(UserTokenCount $model) {
                        return $model->user->first_name . ' ' . $model->user->last_name;
                    }
                ],
                'count',
                'served',
                [
                    'label' => 'Date',
                    'value' => function(UserTokenCount $model) {
                        return date('d M, Y', strtotime($model->date));
                    }
                ],
            ],
        ]); ?>

    </div>

</div>