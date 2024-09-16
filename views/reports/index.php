<?php

use app\models\databaseObjects\Queue;
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
                    'value' => function(Queue $model) {
                        return $model->user->first_name . ' ' . $model->user->last_name;
                    }
                ],
                'token',
                [
                    'label' => 'Date',
                    'value' => function(Queue $model) {
                        return date('d M, Y', strtotime($model->date)) . ' ' . $model->time;
                    }
                ],
                [
                    'label' => 'Ref',
                    'value' => function(Queue $model) {
                        if (is_null($model->trail_id)) return '';

                        return $model->trail->user->first_name . ' ' . $model->trail->user->last_name . ' (#' . $model->trail_id . ')';
                    }
                ],
            ],
        ]); ?>

    </div>

</div>