<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
// $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-screen">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="h-full">
<?php $this->beginBody() ?>

<header id="header">
</header>

<main id="main" role="main" class="flex flex-auto h-full">
    <nav>
        <div class="bg-white shadow-md w-64 rounded-md m-5">
            <div class="flex items-center justify-center h-14">
                <span class="text-xl font-black">QUEUE</span>
            </div>
            <ul class="py-4">
                <li>
                    <a href="/report" class="block px-6 py-2 hover:bg-gray-100">Report</a>
                </li>
                <li>
                    <a href="/users" class="block px-6 py-2 hover:bg-gray-100">Users</a>
                </li>
                <li>
                    <a href="/roles" class="block px-6 py-2 hover:bg-gray-100">Roles</a>
                </li>
                <li>
                    <a href="/queues/kiosk" class="block px-6 py-2 hover:bg-gray-100">Kiosk</a>
                </li>
                <li>
                    <a href="/queues/call" class="block px-6 py-2 hover:bg-gray-100">Call</a>
                </li>
                <li>
                    <a href="/permissions" class="block px-6 py-2 hover:bg-gray-100">Permissions</a>
                </li>
                <li>
                    <a href="/role-permissions" class="block px-6 py-2 hover:bg-gray-100">Role Permissions</a>
                </li>
                <li class="nav-item">
                <?=
                    Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Logout',
                        ['class' => 'px-6 py-2 hover:bg-gray-100 text-red-600 w-full text-left']
                    )
                    . Html::endForm()
                    ?>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container h-full p-4">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs']
            ]) ?>
        <?php endif ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
