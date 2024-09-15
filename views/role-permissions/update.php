<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\RolePermission $model */

$this->title = 'Update Role Permission: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Role Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="role-permissions-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
