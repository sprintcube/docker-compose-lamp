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
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'sessionRedis' => [
			'class' => 'yii\redis\Session',
		],
		'cacheRedis' => [
			'class' => 'yii\redis\Cache',
		],
        'imageCreator' => [
			'class' => 'app\components\ImageCreator'
        ],
        'adminAPI' => [
			'send' => [
				'a' => ['class' => 'app\components\adminService\attributer\send\Attribute'],
				'f' => ['class' => 'app\components\adminService\attributer\send\Filter'],
				'p' => ['class' => 'app\components\adminService\attributer\send\Photogallery'],
				'pm' => ['class' => 'app\components\adminService\attributer\send\Parameters'],
				'ds' => ['class' => 'app\components\adminService\attributer\send\Datasets']
			],
			'update' => [
				'a' => ['class' => 'app\components\adminService\attributer\update\Attribute'],
				'f' => ['class' => 'app\components\adminService\attributer\update\Filter'],
				'p' => ['class' => 'app\components\adminService\attributer\update\Photogallery'],
				'pm' => ['class' => 'app\components\adminService\attributer\update\Parameters'],
				'ds' => ['class' => 'app\components\adminService\attributer\update\Datasets']
			],
			'delete' => [
				'a' => ['class' => 'app\components\adminService\attributer\delete\Attribute'],
				'f' => ['class' => 'app\components\adminService\attributer\delete\Filter'],
				'p' => ['class' => 'app\components\adminService\attributer\delete\Photogallery'],
				'pm' => ['class' => 'app\components\adminService\attributer\delete\Parameters'],
				'ds' => ['class' => 'app\components\adminService\attributer\delete\Datasets']
			],
			'show' => [
				'a' => ['class' => 'app\components\adminService\attributer\show\Attribute'],
				'f' => ['class' => 'app\components\adminService\attributer\show\Filter'],
				'p' => ['class' => 'app\components\adminService\attributer\show\Photogallery'],
				'pm' => ['class' => 'app\components\adminService\attributer\show\Parameters'],
				'tc' => ['class' => 'app\components\adminService\attributer\show\TableColumns'],
				'ds' => ['class' => 'app\components\adminService\attributer\show\Datasets']
			]
        ],
        'urlManager' => [
			 'class' => 'yii\web\UrlManager',
			 'showScriptName' => false,
			 'enableStrictParsing' => true,
			 'enablePrettyUrl' => true, 
			 'rules' => [
				'defaultRoute' => 'site/index',
				'accounts/<service:\w+>' => '/site/account-service',
				'accounts/accept/<service:\w+>' => '/site/service-code-center',
				'admin' => 'admin/index',
				'admin/auth' => 'admin/auth',
				'admin/api/<svc:\w+>/<subSVC:\w+>' => '/admin/admin-service',
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
        'smsCoder' => ['class' => 'app\components\SMSCode'],
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
