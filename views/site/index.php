<?php

/** @var yii\web\View $this */
/** @var app\models\databaseObjects\Article[] $articles */

$this->title = 'Huesio - Home';
$hrefPrefix = Yii::$app->params['appBaseUrl'];
$articleIndex = 0;
?>

<?php if (count($articles) < 7) : ?>
<div class="article-home">
    <div class="flex px-1 lg:px-8 md:px-4 justify-center">
        <img src="/images/we-are-setting-up.webp" alt="Nothing to see" draggable="false">
    </div>
</div>
<?php return; endif; ?>

<div class="article-home">
    <div class="flex px-1 lg:px-8 md:px-4">

        <div class="w-full lg:w-1/2 md:w-1/2 pr-0 lg:pr-2 md:pr-2">
            <div class="w-full">
                <a href="<?= $hrefPrefix . $articles[$articleIndex]->handle ?>">
                    <h4 class="text-3xl text-center p-3"><?= $articles[$articleIndex]->title ?></h4>
                    <img src="<?= Yii::$app->params['imgCdnBaseUrl'] . $articles[$articleIndex]->file->uuid . '.' . pathinfo($articles[$articleIndex]->file->name, PATHINFO_EXTENSION) ?>" alt="<?= $articles[$articleIndex]->title ?>" loading="lazy">
                    <h6 class="text-base p-3"><?= $articles[$articleIndex]->description ?></h6>
                    <?php $articleIndex++; ?>
                </a>
            </div>
            <div class="flex">
                <div class="w-1/2 px-1">
                    <a href="<?= $hrefPrefix . $articles[$articleIndex]->handle ?>">
                        <img src="<?= Yii::$app->params['imgCdnBaseUrl'] . $articles[$articleIndex]->file->uuid . '.' . pathinfo($articles[$articleIndex]->file->name, PATHINFO_EXTENSION) ?>" alt="<?= $articles[$articleIndex]->title ?>" loading="lazy">
                        <h4 class="text-xl px-3 pt-3"><?= $articles[$articleIndex]->title ?></h4>
                        <h6 class="text-base px-3 pb-3"><?= $articles[$articleIndex]->description ?></h6>
                        <?php $articleIndex++; ?>
                    </a>
                </div>
                <div class="w-1/2 px-1">
                    <a href="<?= $hrefPrefix . $articles[$articleIndex]->handle ?>">
                        <img src="<?= Yii::$app->params['imgCdnBaseUrl'] . $articles[$articleIndex]->file->uuid . '.' . pathinfo($articles[$articleIndex]->file->name, PATHINFO_EXTENSION) ?>" alt="<?= $articles[$articleIndex]->title ?>" loading="lazy">
                        <h4 class="text-xl px-3 pt-3"><?= $articles[$articleIndex]->title ?></h4>
                        <h6 class="text-base px-3 pb-3"><?= $articles[$articleIndex]->description ?></h6>
                        <?php $articleIndex++; ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="flex w-full lg:w-1/2 md:w-1/2 pl-0 lg:pl-2 md:pl-2">
            <div class="w-full lg:w-1/2 md:w-1/2">
                <div class="w-full px-0 lg:px-1 md:px-1">
                    <a href="<?= $hrefPrefix . $articles[$articleIndex]->handle ?>">
                        <img src="<?= Yii::$app->params['imgCdnBaseUrl'] . $articles[$articleIndex]->file->uuid . '.' . pathinfo($articles[$articleIndex]->file->name, PATHINFO_EXTENSION) ?>" alt="<?= $articles[$articleIndex]->title ?>" loading="lazy">
                        <h4 class="text-xl px-3 pt-3"><?= $articles[$articleIndex]->title ?></h4>
                        <h6 class="text-base px-3 pb-3"><?= $articles[$articleIndex]->description ?></h6>
                        <?php $articleIndex++; ?>
                    </a>
                </div>
                <div class="w-full px-0 lg:px-1 md:px-1">
                    <a href="<?= $hrefPrefix . $articles[$articleIndex]->handle ?>">
                        <img src="<?= Yii::$app->params['imgCdnBaseUrl'] . $articles[$articleIndex]->file->uuid . '.' . pathinfo($articles[$articleIndex]->file->name, PATHINFO_EXTENSION) ?>" alt="<?= $articles[$articleIndex]->title ?>" loading="lazy">
                        <h4 class="text-xl px-3 pt-3"><?= $articles[$articleIndex]->title ?></h4>
                        <h6 class="text-base px-3 pb-3"><?= $articles[$articleIndex]->description ?></h6>
                        <?php $articleIndex++; ?>
                    </a>
                </div>
            </div>
            <div class="w-full lg:w-1/2 md:w-1/2">
                <div class="w-full px-0 lg:px-1 md:px-1">
                    <a href="<?= $hrefPrefix . $articles[$articleIndex]->handle ?>">
                        <img src="<?= Yii::$app->params['imgCdnBaseUrl'] . $articles[$articleIndex]->file->uuid . '.' . pathinfo($articles[$articleIndex]->file->name, PATHINFO_EXTENSION) ?>" alt="<?= $articles[$articleIndex]->title ?>" loading="lazy">
                        <h4 class="text-xl px-3 pt-3"><?= $articles[$articleIndex]->title ?></h4>
                        <h6 class="text-base px-3 pb-3"><?= $articles[$articleIndex]->description ?></h6>
                        <?php $articleIndex++; ?>
                    </a>
                </div>
                <div class="w-full px-0 lg:px-1 md:px-1">
                    <a href="<?= $hrefPrefix . $articles[$articleIndex]->handle ?>">
                        <img src="<?= Yii::$app->params['imgCdnBaseUrl'] . $articles[$articleIndex]->file->uuid . '.' . pathinfo($articles[$articleIndex]->file->name, PATHINFO_EXTENSION) ?>" alt="<?= $articles[$articleIndex]->title ?>" loading="lazy">
                        <h4 class="text-xl px-3 pt-3"><?= $articles[$articleIndex]->title ?></h4>
                        <h6 class="text-base px-3 pb-3"><?= $articles[$articleIndex]->description ?></h6>
                        <?php $articleIndex++; ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>