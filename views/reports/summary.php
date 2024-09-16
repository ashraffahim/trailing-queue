<?php

use app\models\databaseObjects\Queue;
use app\models\databaseObjects\User;
use app\models\databaseObjects\UserTokenCount;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $queue */
/** @var string $date */

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
                    <label for="date" class="input-label-classic">Date</label>
                    <input type="date" name="date" id="date" class="input-classic" value="<?= $date ?>">
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