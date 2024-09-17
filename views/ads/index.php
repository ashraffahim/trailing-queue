<?php

/** @var yii\web\View $this */
/** @var array $files */

use app\assets\AdsAsset;
use yii\helpers\Html;

$this->title = 'Ads';
$this->params['breadcrumbs'][] = $this->title;

AdsAsset::register($this);
?>
<div class="ads-index">
    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="relative flex flex-col mt-6">
        <div class="flex justify-center items-center text-lg w-full h-28 inset-0 border-2 border-dashed border-gray-400 text-gray-400">Select images / videos</div>
        <input type="file" id="file-input" class="file-collector absolute w-full h-28 inset-0 opacity-0" accept="image/*,video/*" multiple>
    </div>

    <div class="flex max-w-sm mt-6">
        <div class="grid-view">
            <table class="table-classic">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Size</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 0;
                    foreach ($files as $file) :
                        $index++;
                    ?>
                        <tr>
                            <td><?= $index ?></td>
                            <td><?= basename($file) ?></td>
                            <td><?= filesize($file); ?></td>
                            <td><?= Html::a('Delete', ['delete', 'name' => basename($file)], [
                                    'class' => 'btn-danger-muted mt-0',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>