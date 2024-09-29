<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Kiosk asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class KioskAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [];

    public $js = [
        'js/kiosk.js?v=1725092300',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];
    
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
