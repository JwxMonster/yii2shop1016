<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    //设置语言
    'language'=>'zh-CN',
    //布局文件设置(false表示已经关闭)
    //'layout'=>'mine',mine是自己定义的视图文件的名字
   'layout'=>false,
    //设置默认首页
    'defaultRoute'=>'goods/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            //'identityClass' => 'common\models\User',
            'identityClass' =>\frontend\models\Member::className(),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //地址重写，地址美化，伪静态

        'urlManager' => [
            'enablePrettyUrl' => true,//启用URL美化
            'showScriptName' => false,//是否显示脚本文件(index.php)
            //'suffix'=>'.html',//伪静态后缀
            'rules' => [
            ],
        ],
        'sms'=>[
            'class'=>\frontend\components\AliyunSms::className(),
            'accessKeyId'=>'LTAIeDr7TDDYdg1S',
            'accessKeySecret'=>'VM1TsC3sNd4CYGksUEfceWjhCymq9x',
            'signName'=>'小金茶坊',
            'templateCode'=>'SMS_80265045'
        ]

    ],
    'params' => $params,
];
