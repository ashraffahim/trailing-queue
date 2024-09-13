<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Turf[] $model */

$this->title = 'Turves';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turf-index">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-lg">
        <div class="flex justify-end">
            <?= Html::a('Create', '/turves/create', ['class' => 'btn-classic-muted mt-0']) ?>
        </div>
        <ul class="stacked-list">
            <?php
            foreach ($model as $turf) :
            ?>
                <li>
                    <div>
                        <div>
                            <p><?= Html::a($turf->name, ['/turves/view/' . $turf->nid], ['class' => '']) ?></p>
                            <p><?= $turf->address ?></p>
                        </div>
                    </div>
                    <div>
                        <p>
                            <?= Html::a('Edit', ['/turves/update/' . $turf->nid], ['class' => '']) ?>
                             / 
                            <?= Html::a('Delete', ['/turves/delete/' . $turf->nid], [
                                'class' => 'text-red-600',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ]
                            ]) ?>
                        </p>
                        <p></p>
                    </div>
                </li>
            <?php
            endforeach;
            ?>
        </ul>
    </div>

</div>