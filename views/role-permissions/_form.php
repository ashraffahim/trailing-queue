<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\RolePermission $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="role-permissions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'permission_id')->textInput() ?>

    <?= $form->field($model, 'role_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
