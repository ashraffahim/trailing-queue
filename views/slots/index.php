<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Turf[] $model */

$this->title = 'Select a turf';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turf-index">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-lg">
        <ul class="stacked-list">
            <?php
            foreach ($model as $turf) :
            ?>
                <li class="cursor-pointer hover:bg-gray-100 rounded-md pl-2" onclick="location.href='/slots/update/<?= $turf->nid ?>'">
                    <div>
                        <div>
                            <p><?= $turf->name ?></p>
                            <p><?= $turf->address ?></p>
                        </div>
                    </div>
                    <div>
                        <p></p>
                        <p></p>
                    </div>
                </li>
            <?php
            endforeach;
            ?>
        </ul>
    </div>

</div>