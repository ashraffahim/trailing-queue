<?php

use app\components\QueueManager;
use app\models\databaseObjects\Queue;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $queue */
/** @var string $from */
/** @var string $to */
/** @var string $token */

$this->title = 'Queue';
$this->params['breadcrumbs'][] = ['label' => 'Report', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-queue">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-4xl">

        <form>
            <div class="flex items-end gap-x-2 my-6">
                <div class="form-group">
                    <label for="from" class="input-label-classic">From</label>
                    <input type="date" name="from" id="from" class="input-classic" value="<?= $from ?>">
                </div>
                <div class="form-group">
                    <label for="to" class="input-label-classic">To</label>
                    <input type="date" name="to" id="to" class="input-classic" value="<?= $to ?>">
                </div>
                <div class="form-group">
                    <label for="token" class="input-label-classic">Token</label>
                    <input type="text" name="token" id="token" class="input-classic" value="<?= $token ?>">
                </div>
                <div class="form-group">
                    <button class="btn-classic">Filter</button>
                </div>
            </div>
        </form>

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
                [
                    'label' => 'Status',
                    'value' => function(Queue $model) {
                        return QueueManager::STATUS_NAME[$model->status];
                    }
                ],
                'call_time',
                'recall_time',
                'recall_count',
                'end_time',
            ],
        ]); ?>

    </div>

</div>