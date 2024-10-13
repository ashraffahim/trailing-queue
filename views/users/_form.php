<?php

use app\assets\SelectClassicAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\User $model */
/** @var app\models\databaseObjects\Room[] $rooms */
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

            <div class="flex gap-x-2 sm:max-w-80">
                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'class' => 'input-classic']) ?>
    
                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'class' => 'input-classic']) ?>
            </div>

            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-80']) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-80']) ?>

            <div class="flex gap-x-2 sm:max-w-80">
                <div class="form-group">
                    <label class="input-label-classic">Room</label>
                    <div class="select-classic close-on-blur sm:max-w-60" data-name="User[room_id]" tabindex="0">
                        <div class="select-value"><?= !is_null($model->room) ? $model->room->name : '' ?><input type="hidden" name="User[room_id]" value="<?= $model->room_id ?>"></div>
                        <div class="select-options">
                            <a class="select-option" data-value="">&nbsp;</a>
                            <?php foreach ($rooms as $room): ?>
                                <a class="select-option" data-value="<?= $room->id ?>"><?= $room->name ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label class="input-label-classic">Role</label>
                    <div class="select-classic close-on-blur sm:max-w-60" data-name="User[role_id]" tabindex="0">
                        <div class="select-value"><?= !is_null($model->role) ? $model->role->name : '' ?><input type="hidden" name="User[role_id]" value="<?= $model->role_id ?>"></div>
                        <div class="select-options">
                            <a class="select-option" data-value="">&nbsp;</a>
                            <?php foreach ($roles as $role): ?>
                                <a class="select-option" data-value="<?= $role->id ?>"><?= $role->name ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?= $form->field($model, 'desk')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-80']) ?>

            <?= $form->field($model, 'is_active')->checkbox() ?>

            <?php $model->password_hash = ''; ?>
            
            <?php if (!is_null($model->id)) : ?>
                <div class="form-group">
                    <label class="input-label-classic">
                        <input type="hidden" name="change_password" value="0">
                        <input type="checkbox" name="change_password" value="1" id="change-password"> Change password
                    </label>
                </div>

                <div id="password-input-container" style="display: none;">
                    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>
                </div>
            <?php else : ?>
                <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true, 'class' => 'input-classic sm:max-w-60']) ?>
            <?php endif; ?>

        </div>

        <div class="form-group flex justify-end">
            <?= Html::a('Cancel', is_null($model->id) ? '/users' : '/users/view/' . $model->id, $options = ['class' => 'btn-muted']) ?>
            <?= Html::submitButton(is_null($model->id) ? 'Create' : 'Update', ['class' => 'btn-classic ml-3']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    window.onload = () => {
        let changedPasswordValue = '';
        $('#change-password').on('click', function() {
            if (this.checked) {
                $('#password-input-container').show();
                $('#user-password_hash').val(changedPasswordValue);
            } else {
                $('#password-input-container').hide();
                changedPasswordValue = $('#user-password_hash').val();
                $('#user-password_hash').val('');
            }
        });
    }
</script>