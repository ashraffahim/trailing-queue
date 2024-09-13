<?php

use app\assets\SlotsAsset;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Turf $turf */
/** @var app\models\databaseObjects\Slot[] $model */

$this->title = 'Update Slots: ' . $turf->name;
$this->params['breadcrumbs'][] = ['label' => 'Slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $turf->name;

SlotsAsset::register($this);
$this->registerJs('selectClassic()');

?>
<div class="slot-update">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="slot-form">

        <div class="mt-10 sm:w-full sm:max-w-3xl">
            <div class="form-group flex justify-between">
                <div class="flex gap-x-2">

                    <div class="form-group">
                        <label for="from-time" class="input-label-classic">From</label>
                        <input type="time" id="from-time" class="input-classic w-28">
                    </div>

                    <div class="mt-9">—</div>

                    <div class="form-group">
                        <label for="to-time" class="input-label-classic">To</label>
                        <input type="time" id="to-time" class="input-classic w-28">
                    </div>

                    <div class="form-group">
                        <label for="duration" class="input-label-classic">Duration</label>
                        <div class="input-group">
                            <input type="number" id="duration" class="input-classic w-10 text-right">
                            <div class="select-classic close-on-blur" id="duration-type" data-name="duration_type" tabindex="0">
                                <div class="select-value">hr<input type="hidden" name="duration_type" value="hr"></div>
                                <div class="select-options">
                                    <a class="select-option" data-value="hr">hr</a>
                                    <a class="select-option" data-value="min">min</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Buffer" class="input-label-classic">Buffer</label>
                        <div class="input-group">
                            <input type="number" id="buffer" class="input-classic w-10 text-right">
                            <div class="select-classic close-on-blur" id="buffer-type" data-name="buffer_type" tabindex="0">
                                <div class="select-value">hr<input type="hidden" name="buffer_type" value="hr"></div>
                                <div class="select-options">
                                    <a class="select-option" data-value="hr">hr</a>
                                    <a class="select-option" data-value="min">min</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::button('Generate', [
                            'id' => 'generate-slots',
                            'class' => 'btn-classic-muted mt-8'
                        ]) ?>
                    </div>

                </div>
                <div class="flex items-end">
                    <?= Html::a('Cancel', '/slots', $options = ['class' => 'btn-muted mt-0']) ?>
                    <?= Html::submitButton('Update', ['id' => 'update-slots', 'class' => 'btn-classic ml-3 mt-0']) ?>
                </div>
            </div>
        </div>

        <div class="mt-6 sm:max-w-6xl">
            <div id="slots-grid" class="flex flex-col overflow-x-auto">
                <div class="flex">
                    <div class="inline-flex items-center justify-center sticky left-0 w-12 h-28 bg-gray-100 rotate-180 [writing-mode:vertical-lr]">Sunday</div>
                    <div id="slot-0" class="flex flex-nowrap"></div>
                </div>
                <div class="flex">
                    <div class="inline-flex items-center justify-center sticky left-0 w-12 h-28 bg-gray-100 rotate-180 [writing-mode:vertical-lr]">Monday</div>
                    <div id="slot-1" class="flex flex-nowrap"></div>
                </div>
                <div class="flex">
                    <div class="inline-flex items-center justify-center sticky left-0 w-12 h-28 bg-gray-100 rotate-180 [writing-mode:vertical-lr]">Tuesday</div>
                    <div id="slot-2" class="flex flex-nowrap"></div>
                </div>
                <div class="flex">
                    <div class="inline-flex items-center justify-center sticky left-0 w-12 h-28 bg-gray-100 rotate-180 [writing-mode:vertical-lr]">Wednesday</div>
                    <div id="slot-3" class="flex flex-nowrap"></div>
                </div>
                <div class="flex">
                    <div class="inline-flex items-center justify-center sticky left-0 w-12 h-28 bg-gray-100 rotate-180 [writing-mode:vertical-lr]">Thursday</div>
                    <div id="slot-4" class="flex flex-nowrap"></div>
                </div>
                <div class="flex">
                    <div class="inline-flex items-center justify-center sticky left-0 w-12 h-28 bg-gray-100 rotate-180 [writing-mode:vertical-lr]">Friday</div>
                    <div id="slot-5" class="flex flex-nowrap"></div>
                </div>
                <div class="flex">
                    <div class="inline-flex items-center justify-center sticky left-0 w-12 h-28 bg-gray-100 rotate-180 [writing-mode:vertical-lr]">Saturday</div>
                    <div id="slot-6" class="flex flex-nowrap"></div>
                </div>
            </div>
        </div>

    </div>

</div>