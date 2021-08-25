<?php

$db = require __DIR__ . '/db.php';

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
			 'showScriptName' => false,
			 'enablePrettyUrl' => true,
			 'rules' => [
				'defaultRoute' => 'site/index',
				'news' => 'news/index',
				'news/<contentId:\d+>' => 'news/view',
				'passport' => 'passport/service',
				'objects' => 'objects/index',
				'objects/search' => 'objects/object',
				'objects/<objectId:\d+>' => 'objects/view'
			 ]
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
