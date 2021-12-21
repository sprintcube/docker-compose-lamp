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
        'adminService' => [
			'attributer' => [
				'send' => [
					'attribute' => ['class' => 'app\components\adminService\attributer\send\Attribute'],
					'filter' => ['class' => 'app\components\adminService\attributer\send\Filter'],
					'photogallery' => ['class' => 'app\components\adminService\attributer\send\Photogallery'],
					'parameters' => ['class' => 'app\components\adminService\attributer\send\Parameters'],
					'datasets' => ['class' => 'app\components\adminService\attributer\send\Datasets']
				],
				'update' => [
					'attribute' => ['class' => 'app\components\adminService\attributer\update\Attribute'],
					'filter' => ['class' => 'app\components\adminService\attributer\update\Filter'],
					'photogallery' => ['class' => 'app\components\adminService\attributer\update\Photogallery'],
					'parameters' => ['class' => 'app\components\adminService\attributer\update\Parameters'],
					'datasets' => ['class' => 'app\components\adminService\attributer\update\Datasets']
				],
				'delete' => [
					'attribute' => ['class' => 'app\components\adminService\attributer\delete\Attribute'],
					'filter' => ['class' => 'app\components\adminService\attributer\delete\Filter'],
					'photogallery' => ['class' => 'app\components\adminService\attributer\delete\Photogallery'],
					'parameters' => ['class' => 'app\components\adminService\attributer\delete\Parameters'],
					'datasets' => ['class' => 'app\components\adminService\attributer\delete\Datasets']
				],
				'show' => [
					'attribute' => ['class' => 'app\components\adminService\attributer\show\Attribute'],
					'filter' => ['class' => 'app\components\adminService\attributer\show\Filter'],
					'photogallery' => ['class' => 'app\components\adminService\attributer\show\Photogallery'],
					'parameters' => ['class' => 'app\components\adminService\attributer\show\Parameters'],
					'tableColumns' => ['class' => 'app\components\adminService\attributer\show\TableColumns'],
					'datasets' => ['class' => 'app\components\adminService\attributer\show\Datasets']
				]
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
