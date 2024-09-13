<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Turf $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Turves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/turf-image-upload.js', ['depends' => '\yii\web\JqueryAsset']);

?>
<div class="turf-view">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="flex gap-5">
        <div class="flex-auto lg:max-w-sm">
            <div class="mt-6 mx-5 p-5 shadow-lg rounded-md">
                <dl>
                    <div>
                        <dt></dt>
                        <dd>
                            <div class="flex">
                                <?= Html::a('Update', ['turves/update/' . $model->nid], ['class' => 'btn-muted mt-0']) ?>
                                <?= Html::a('Delete', ['turves/delete/' . $model->nid], [
                                    'class' => 'btn-danger-muted mt-0',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </dd>
                    </div>
                    <div>
                        <dt>ID</dt>
                        <dd><?= $model->nid ?></dd>
                    </div>
                    <div>
                        <dt>Name</dt>
                        <dd><?= $model->name ?></dd>
                    </div>
                    <div>
                        <dt>Address</dt>
                        <dd><?= $model->address ?></dd>
                    </div>
                </dl>
            </div>
        </div>
    
        <div class="flex-auto lg:max-w-lg">
            <div class="mt-6">
                <div class="relative">
                    <div id="image-input-dropbox" class="absolute inset-x-0 inset-y-0 flex justify-center items-center border-dashed border-2 border-gray-200 text-gray-400"><span class="font-semibold text-indigo-600 mr-1.5">Select</span> / Drop image</div>
                    <input type="file" name="image" id="image-input" class="relative w-full h-40 opacity-0" multiple>
                </div>
    
                <div class="mt-6">
                    <ul id="uploaded-images" class="stacked-list"></ul>
                </div>
    
                <div class="flex mt-6 justify-end">
                    <button id="upload-image" class="btn-classic" disabled>Upload</button>
                </div>
            </div>

        </div>
    </div>

</div>