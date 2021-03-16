<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'https://unpkg.com/swiper/swiper-bundle.min.css'
    ];
    public $js = [
        'https://kit.fontawesome.com/97c3285af2.js',
        'https://unpkg.com/swiper/swiper-bundle.min.js',
        'https://unpkg.com/react@17/umd/react.development.js',
        'https://unpkg.com/react@17/umd/react-dom.development.js',
        'https://cdnjs.cloudflare.com/ajax/libs/babel-core/6.1.19/browser.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
