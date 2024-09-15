<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Call asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CallAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [];

    public $js = [
        'js/call.js?v=1725092200',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];
    
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
