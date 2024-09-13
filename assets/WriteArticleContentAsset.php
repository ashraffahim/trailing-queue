<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Files controller asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class WriteArticleContentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/write-article-content.js?v=123456790'
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_END];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
