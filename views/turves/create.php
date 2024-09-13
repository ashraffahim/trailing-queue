<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Turf $model */

$this->title = 'Create Turf';
$this->params['breadcrumbs'][] = ['label' => 'Turves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turf-create">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>
    <!-- <div class="flex min-h-full flex-1 flex-col justify-center sm:mx-auto sm:w-full sm:max-w-sm px-6 py-12 lg:px-8"> -->
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    <!-- </div> -->

</div>
