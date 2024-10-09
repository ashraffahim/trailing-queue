<?php

use app\models\databaseObjects\UserTokenCount;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $queue */
/** @var string $from */
/** @var string $to */

$this->title = 'Summary';
$this->params['breadcrumbs'][] = ['label' => 'Report', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-summary">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-2xl">
        
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
                    <button class="btn-classic">Filter</button>
                </div>
            </div>
        </form>

        <?= GridView::widget([
            'dataProvider' => $queue,
            'tableOptions' => ['class' => 'table-classic'],
            'summary' => '{begin} - {end} / {totalCount}',
            'layout' => '{summary}{items}{pager}',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'Name',
                    'format' => 'raw',
                    'value' => function(UserTokenCount $model) {
                        return Html::a($model->user->first_name . ' ' . $model->user->last_name, '/reports/summary/' . $model->user_id . '/' . $model->date, ['class' => 'underline hover:text-emerald-300']);
                    }
                ],
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