<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\RolePermission $model */

$this->title = 'Create Role Permission';
$this->params['breadcrumbs'][] = ['label' => 'Role Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-permissions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
