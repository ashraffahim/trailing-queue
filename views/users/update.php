<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\User $model */
/** @var app\models\databaseObjects\Role[] $roles */

$this->title = 'Update User: ' . $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->first_name . ' ' . $model->last_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
    ]) ?>

</div>