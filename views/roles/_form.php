<?php

use app\assets\SelectClassicAsset;
use app\components\PermissionManager;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Role $model */
/** @var yii\widgets\ActiveForm $form */

SelectClassicAsset::register($this);
$this->registerJs('selectClassic()');
?>

<div class="roles-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'labelOptions' => ['class' => 'input-label-classic'],
            'errorOptions' => ['class' => 'input-error-text-classic']
        ]
    ]); ?>

    <div class="mt-10 sm:w-full sm:max-w-lg">
        <div class="border-b border-gray-900/10 pb-12 space-y-5">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-xs']) ?>

            <?= $form->field($model, 'token_prefix')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-xs']) ?>

            <div class="form-group">
                <label class="input-label-classic">Task</label>
                <div class="select-classic close-on-blur sm:max-w-60" data-name="Role[task]" tabindex="0">
                    <div class="select-value"><?= is_null($model->task) ? '' : PermissionManager::ROLES[$model->task] ?><input type="hidden" name="Role[task]" value="<?= $model->task ?>"></div>
                    <div class="select-options">
                        <a class="select-option" data-value="">&nbsp;</a>
                        <?php foreach (PermissionManager::ROLES as $id => $name): ?>
                            <a class="select-option" data-value="<?= $id ?>"><?= $name ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?= $form->field($model, 'is_open')->checkbox() ?>

            <?= $form->field($model, 'is_kiosk_visible')->checkbox() ?>
        </div>

        <div class="form-group flex justify-end">
            <?= Html::a('Cancel', is_null($model->id) ? ['roles/view'] : ['view', 'id' => $model->id], $options = ['class' => 'btn-muted']) ?>
            <?= Html::submitButton(is_null($model->id) ? 'Create' : 'Update', ['class' => 'btn-classic ml-3']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>