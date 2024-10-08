<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Monitor asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MonitorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [];

    public $js = [
        'js/monitor.js?v=1725092200',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];
    
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
