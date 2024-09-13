<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Booking $model */
/** @var app\models\databaseObjects\Turf[] $turves */
/** @var yii\widgets\ActiveForm $form */

$this->registerJsFile('@web/js/lib/select-classic.js', ['depends' => '\yii\web\JqueryAsset']);
$this->registerJs('selectClassic()');

$errorClass = '';
$errorText = '';

if ($model->hasErrors('user_id')) {
    $errorClass = ' has-error';
    $errorText = $model->getErrors('user_id');
}

?>

<div class="booking-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'labelOptions' => ['class' => 'input-label-classic'],
            'errorOptions' => ['class' => 'input-error-text-classic']
        ]
    ]); ?>

    <div class="mt-10 sm:w-full sm:max-w-lg">
        
        <div class="border-b border-gray-900/10 pb-12 space-y-5">

            <div class="form-group<?= $errorClass ?>">
                <label for="select-turf" class="input-label-classic">Turf</label>
                <div id="select-turf" class="select-classic sm:max-w-xs close-on-blur" data-name="user_nid" tabindex="0">
                    <div class="select-value"><?= is_null($model->turf) ? '' : ($model->turf->name . '<input type="hidden" name="user_nid" value="' . $model->turf->nid . '">') ?></div>
                    <div class="select-options">
                        <?php
                        foreach ($turves as $turf) :
                        ?>

                        <a class="select-option" data-value="<?= $turf->nid ?>"><?= $turf->name ?></a>

                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
                <div class="input-error-classic"><?= $errorText ?></div>
            </div>

            <?= $form->field($model, 'date')->input('date', ['class' => 'input-classic sm:max-w-36']) ?>
            
            <?= $form->field($model, 'email')->input('email', ['class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'phone')->textInput(['class' => 'input-classic sm:max-w-60']) ?>

        </div>
        
        <div class="form-group flex justify-end">
            <?= Html::a('Cancel', is_null($model->id) ? '/bookings' : '/bookings/view/' . $model->nid, $options = ['class' => 'btn-muted']) ?>
            <?= Html::submitButton(is_null($model->id) ? 'Create' : 'Update', ['class' => 'btn-classic ml-3']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
