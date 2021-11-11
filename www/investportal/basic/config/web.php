<?php

$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'DSFgksdifhiw899734hekfDFGisjdfi9374',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'imageCreator' => [
			'class' => 'yii\components\CrossFormatsImageCreator'
        ],
        'urlManager' => [
			 'class' => 'yii\web\UrlManager',
			 'enablePrettyUrl' => true,
             'showScriptName' => false,
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
        'portalUserService' => ['class' => 'app\components\SignService'],
        'portalCommunicationService' => ['class' => 'app\components\CommunicationService'],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ]
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '192.168.10.20'],
    ];
}


return $config;
