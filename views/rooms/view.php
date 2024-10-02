<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Room $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="room-view">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-lg">
        <div class="flex mt-6 right">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn-muted mt-0']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn-danger-muted mt-0',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="mt-6 p-5 shadow-lg rounded-md">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table-classic'],
                'attributes' => [
                    'name',
                    'floor',
                ],
            ]) ?>
        </div>
    </div>

</div>