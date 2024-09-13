<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'labelOptions' => ['class' => 'input-label-classic'],
            'errorOptions' => ['class' => 'input-error-text-classic']
        ]
    ]); ?>


    <div class="mt-10 sm:w-full sm:max-w-lg">
        <div class="border-b border-gray-900/10 pb-12 space-y-5">

            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?php $model->password_hash = ''; ?>
            <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

        </div>

        <div class="form-group flex justify-end">
            <?= Html::a('Cancel', is_null($model->id) ? '/users' : '/users/view/' . $model->nid, $options = ['class' => 'btn-muted']) ?>
            <?= Html::submitButton(is_null($model->id) ? 'Create' : 'Update', ['class' => 'btn-classic ml-3']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>