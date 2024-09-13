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

    <p>
        <?= Html::a('Update', ['update', 'nid' => $model->nid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'nid' => $model->nid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table-classic'],
        'attributes' => [
            'nid',
            'first_name',
            'last_name',
            'username',
            'email:email',
        ],
    ]) ?>

</div>