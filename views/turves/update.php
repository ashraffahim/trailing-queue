<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Turf $model */

$this->title = 'Update Turf: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Turves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="turf-update">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
