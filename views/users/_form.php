<?php

use app\assets\SelectClassicAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\User $model */
/** @var app\models\databaseObjects\Role[] $roles */
/** @var yii\widgets\ActiveForm $form */

SelectClassicAsset::register($this);
$this->registerJs('selectClassic()');
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

            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'floor')->textInput(['class' => 'input-classic sm:max-w-60']) ?>

            <?= $form->field($model, 'room')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

            <div class="form-group">
                <label class="input-label-classic">Role</label>
                <div class="select-classic close-on-blur sm:max-w-60" id="duration-type" data-name="User[role_id]" tabindex="0">
                    <div class="select-value"><input type="hidden" name="User[role_id]" value="<?= $model->role_id ?>"></div>
                    <div class="select-options">
                        <a class="select-option" data-value="">&nbsp;</a>
                        <?php foreach ($roles as $role): ?>
                            <a class="select-option" data-value="<?= $role->id ?>"><?= $role->name ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?= $form->field($model, 'is_open')->checkbox(['disabled' => true]) ?>

            <?php $model->password_hash = ''; ?>
            <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>

        </div>

        <div class="form-group flex justify-end">
            <?= Html::a('Cancel', is_null($model->id) ? '/users' : '/users/view/' . $model->id, $options = ['class' => 'btn-muted']) ?>
            <?= Html::submitButton(is_null($model->id) ? 'Create' : 'Update', ['class' => 'btn-classic ml-3']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>