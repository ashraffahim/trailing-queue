<?php

use app\models\databaseObjects\Booking;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-2xl">
        <div class="flex justify-end">
            <?= Html::a('<i class="fa fa-plus mt-0.5"></i> Create', '/bookings/create', ['class' => 'btn-classic-muted mt-0 mb-1']) ?>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table-classic'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'turf_id',
                'user_id',
                'date',
                //'start_time',
                //'end_time',
                [
                    'class' => ActionColumn::class,
                    'urlCreator' => function ($action, Booking $model, $key, $index, $column) {
                        return Url::toRoute([$action, $model->nid]);
                    }
                ],
            ],
        ]); ?>
    </div>

</div>
