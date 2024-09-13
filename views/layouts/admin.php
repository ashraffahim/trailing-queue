<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\components\Util;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => 'Your only unbiased news source']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'unbiased,news,health,fitness,current.events,canada']);
$this->registerMetaTag(['name' => 'og:title', 'content' => 'Huesio']);
$this->registerMetaTag(['name' => 'og:url', 'content' => Yii::$app->params['appBaseUrl']]);
$this->registerMetaTag(['name' => 'og:description', 'content' => 'Your only unbiased news source']);
$this->registerMetaTag(['name' => 'og:image', 'content' => Yii::$app->params['appBaseUrl'] . 'logo.png']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->params['appBaseUrl']]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="min-h-full">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y91DY88F5B"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-Y91DY88F5B');
    </script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1458829588848469"
     crossorigin="anonymous"></script>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="h-full font-roboto">
<?php $this->beginBody() ?>

<header id="header" class="flex justify-center bg-white border-b border-gray-200">
    <div class="container">
        <div class="grid grid-cols-3 justify-between py-3 px-4">
            <div class="brand relative">
                <div class="top-nav-brand-logo"></div>
            </div>
            <nav class="grid items-center justify-center">
                <ul class="list-none whitespace-nowrap child:inline-block child:px-2">
                </ul>
            </nav>
            <div class="account"></div>
        </div>
    </div>
</header>

<main id="main" class="h-full flex-shrink-0" role="main">
    <div class="container h-full my-0 mx-auto pt-3">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="flex py-3 px-5">
    <div class="container">
        <div class="text-gray-500">
            <div class="w-full text-end">&copy; <?= Util::getAppName() . ' ' . date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
