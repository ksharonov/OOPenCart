<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'site',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => '220volt',
    'language' => 'ru-RU',
    'layout' => '@layouts/main',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@assets' => '@app/assets/files'
    ],
    'modules' => [
        'lexema' => [
            'class' => 'app\modules\lexema\Module'
        ],
        'backoffice' => [
            'class' => 'app\modules\backoffice\Module'
        ],
        'api' => [
            'class' => 'app\modules\api\Module'
        ],
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'cart' => [
            'class' => 'app\modules\cart\Module',
            'defaultRoute' => 'index/index'
        ],
        'order' => [
            'class' => 'app\modules\order\Module',
            'defaultRoute' => 'index/index'
        ],
        'catalog' => [
            'layout' => '@layouts/main',
            'class' => 'app\modules\catalog\Module',
            'defaultRoute' => 'index/index'
        ],
        'product' => [
            'layout' => '@layouts/main',
            'class' => 'app\modules\product\Module',
            'defaultRoute' => 'index/index'
        ],
        'profile' => [
            'class' => 'app\modules\profile\Module',
            'defaultRoute' => 'index/index'
        ],
        'error' => [
            'class' => 'app\modules\error\Module',
            'defaultRoute' => 'index/index'
        ],
        'admin' => [
            'layout' => 'main',
            'class' => 'app\modules\admin\Module',
            'modules' => [
                'api' => [
                    'class' => 'app\modules\admin\modules\api\Module'
                ],
                'rbac' => [
                    'class' => 'app\modules\admin\modules\rbac\Module',
                    'controllerMap' => [
                        'assignment' => [
                            'class' => 'app\modules\admin\modules\rbac\controllers\AssignmentController',
                            'userIdentityClass' => 'app\models\db\User',
                            'idField' => 'id',
                            'usernameField' => 'username',
                            'gridViewColumns' => [
                                'id',
                                'username',
                                'email'
                            ]
                        ]
                    ]
                ],
            ],
            'controllerMap' => [
                'elfinder' => [
                    'class' => 'mihaildev\elfinder\Controller',
                    'access' => ['admin', 'manager'],
                    'disabledCommands' => ['netmount'],
                    'roots' => [
                        [
                            'baseUrl' => '@web',
                            'basePath' => '@webroot',
                            'path' => 'files',
                            'name' => 'uploads'
                        ],
                    ],
                ]
            ],
        ],
    ],
    'components' => [
//        'assetManager' => [
//            'class' => 'yii\web\AssetManager',
//            'forceCopy' => true,
//        ],


        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6LfKE14UAAAAAAj2PXczXb4PsQxGoTImgujN9TB4',
            'secret' => '6LfKE14UAAAAAOzi9mFvvdpiIl2O3yL4PDQJUpJd',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        'registry' => [
            'class' => 'app\components\RegistryComponent'
        ],
        'cookie' => [
            'class' => 'app\components\CookieComponent',
        ],
        'view' => [
            'class' => 'app\components\ViewComponent',
        ],
        'catalog' => [
            'class' => 'app\components\CatalogComponent',
        ],
        'setting' => [
            'class' => 'app\components\SettingComponent',
        ],
        'product' => [
            'class' => 'app\components\ProductComponent',
        ],
        'client' => [
            'class' => 'app\components\ClientComponent',
        ],
        'cart' => [
            'class' => 'app\components\CartComponent',
        ],
        'favorite' => [
            'class' => 'app\components\FavoriteComponent',
        ],
        'number' => [
            'class' => 'app\components\NumberComponent',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'ru-RU',
                    'fileMap' => [
                        'basic' => 'basic.php',
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'V60xkqIB0--PkN1SKOXjav1SZtJrjYGZ',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\db\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                'news' => 'site/news',
                'reviews' => 'site/reviews',
                'brands' => 'brand/index',
                'news/<slug:[\w-]+>' => 'site/news',
                'brands/<slug:[\w-]+>' => 'brand/view',
                'brands/<slug:[\w-]+>/products' => 'brand/products',
                'reviews/<slug:[\w-]+>' => 'site/reviews',
                'catalog/category/<id:[\d-]+>' => 'catalog/category',
                'product/<slug:[\w-]+>' => 'product/index',
                '/order/callback/index' => 'order/callback',
                '/order/<id:[\w-]+>' => 'order/index',
                '/profile/order/<id:[\d-]+>' => 'profile/order',
                'order/submit/<id:[\d+]+>' => 'order/submit',
                '/' => '/site/index',
                '<slug:[\w-]+>' => 'page',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
        ],
    /*
      'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
      ],
      ],
     */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '109.195.149.78', '192.168.33.1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
