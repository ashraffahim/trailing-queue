<?php

use app\models\databaseObjects\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-2xl">
        <div class="flex justify-end">
            <?= Html::a('Create', ['create'], ['class' => 'btn-classic-muted mt-0 mb-1']) ?>
        </div>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table-classic'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'first_name',
                'last_name',
                'username',
                'email:email',
                [
                    'class' => ActionColumn::class,
                    'urlCreator' => function ($action, User $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'nid' => $model->nid]);
                    }
                ],
            ],
        ]); ?>

    </div>

</div>