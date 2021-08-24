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
        'css/app.css',
        'https://unpkg.com/swiper/swiper-bundle.min.css'
    ];
    public $js = [
		'https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.6.0.min.js',
        'https://unpkg.com/swiper/swiper-bundle.min.js',
        'https://unpkg.com/react@17/umd/react.development.js',
        'https://unpkg.com/react@17/umd/react-dom.development.js',
        'js/app.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
