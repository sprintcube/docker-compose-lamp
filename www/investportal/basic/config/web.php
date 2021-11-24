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
			'class' => 'app\components\CrossFormatsImageCreator\ImageCreator'
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
				'admin/auth' => 'admin/auth',
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
        'view' => [
            'class' => 'yii\web\View',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true
        ],
        'admin' => [
			'class' => 'yii\web\User',
            'identityClass' => 'app\models\Admin',
            'enableAutoLogin' => true
        ],
        'session' => [ // for use session in console application
            'class' => 'yii\web\Session'
        ],
        'hdfs' => [
			'class' => 'org\apache\hadoop\WebHDFS'
        ],
        'portalReg' => ['class' => 'app\components\SignUp'],
		'portalLogin' => ['class' => 'app\components\SignIn'],
		'asReg' => ['class' => 'app\components\adminSignUp'],
		'asLogin' => ['class' => 'app\components\adminSignIn'],
		'portalExit' => ['class' => 'app\components\SignOut'],
		'asExit' => ['class' => 'app\components\adminSignOut'],
		'autoLogin' => ['class' => 'app\components\autoSignIn'],
		'portalPass' => ['class' => 'app\components\Forgot'],
        'portalCommunicationService' => ['class' => 'app\components\CommunicationService\SMSCode'],
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
