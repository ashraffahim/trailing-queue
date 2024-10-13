<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\User $model */

$this->title = $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

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
                    'first_name',
                    'last_name',
                    'username',
                    'email:email',
                    ['label' => 'Level', 'value' => function($model) { return $model->room->floor; }],
                    ['label' => 'Room', 'value' => function($model) { return $model->room->name; }],
                    'is_active:boolean',
                ],
            ]) ?>
        </div>
    </div>

</div>