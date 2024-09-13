<?php

/** @var app\models\databaseObjects\Article[] $articles */

?>
<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<?php
foreach ($articles as $article) :
?>

<url>
    <loc><?= Yii::$app->params['appBaseUrl'] . $article->handle ?></loc>
    <lastmod><?= is_null($article->updation_date) ? $article->creation_date : $article->updation_date ?></lastmod>
</url>

<?php endforeach; ?>

</urlset>