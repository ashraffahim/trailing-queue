<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var \Exception $exception */

use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$this->title = $name;
?>
<div class="p-10">

    <h1 class="text-5xl"><?= Html::encode($this->title) ?></h1>

    <div class="text-red-900">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <a href="/">Go home</a>
    </p>

</div>
