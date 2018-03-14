<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
//可以自定义当前页面需要加载的css/js
//要想使用必须先加载$depends
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';//静态资源加载的路径 用的别名表示的
    public $baseUrl = '@web';//静态资源的URL地址
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
