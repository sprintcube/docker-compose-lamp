<?php

$db = require __DIR__ . '/db.php';

//Если basic шаблон
$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'imageCreator' => [
			'class' => 'yii\components\CrossFormatsImageCreator'
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
			 'class' => 'yii\web\UrlManager',
			 'baseUrl' => $baseUrl,
			 'enablePrettyUrl' => true,
             'showScriptName' => false,
             'enableStrictParsing' => true,
			 'rules' => [
				'defaultRoute' => 'site/index',
				'accounts/<service:\w+>' => 'site/accountService',
				'accounts/accept/<service:\w+>' => 'site/serviceCodeCenter',
				'admin' => 'admin/index',
				'admin/signIn' => 'admin/auth',
				'admin/api/<svc:\w+>/<subSVC:\w+>' => 'admin/adminService',
				'news' => 'news/index',
				'news/<contentId:\d+>' => 'news/view',
				'passport' => 'passport/service',
				'passport/cart' => 'passport/cartdata',
				'passport/offers' => 'passport/offer',
				'passport/profile' => 'passport/accountedit',
				'passport/services' => 'passport/eventsedit',
				'objects' => 'objects/index',
				'objects/search' => 'objects/object',
				'objects/<objectId:\d+>' => 'objects/view'
			 ]
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\UserService\User',
            'enableAutoLogin' => true,
        ],
        'hdfs' => [
			'class' => 'org\apache\hadoop\WebHDFS'
        ],
        'view' => [
            'class' => 'app\components\View',
        ],
        'session' => [ // for use session in console application
            'class' => 'yii\web\Session'
        ],
        'db' => $db,
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
