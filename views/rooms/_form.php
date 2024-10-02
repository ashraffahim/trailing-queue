<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Room $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="room-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'labelOptions' => ['class' => 'input-label-classic'],
            'errorOptions' => ['class' => 'input-error-text-classic']
        ]
    ]); ?>

    <div class="mt-10 sm:w-full sm:max-w-lg">
        <div class="border-b border-gray-900/10 pb-12 space-y-5">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-xs']) ?>

            <?= $form->field($model, 'floor')->textInput(['class' => 'input-classic sm:max-w-xs']) ?>

        </div>

        <div class="form-group flex justify-end">
            <?= Html::a('Cancel', is_null($model->id) ? ['rooms/view'] : ['view', 'id' => $model->id], $options = ['class' => 'btn-muted']) ?>
            <?= Html::submitButton(is_null($model->id) ? 'Create' : 'Update', ['class' => 'btn-classic ml-3']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>