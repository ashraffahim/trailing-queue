<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Booking $model */
/** @var app\models\databaseObjects\Turf[] $turves */

$this->title = 'Create Booking';
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-create">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'turves' => $turves
    ]) ?>

</div>
