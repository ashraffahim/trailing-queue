<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\User $model */
/** @var app\models\databaseObjects\Room[] $rooms */
/** @var app\models\databaseObjects\Role[] $roles */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'rooms' => $rooms,
        'roles' => $roles,
    ]) ?>

</div>