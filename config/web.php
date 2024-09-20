<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'timeZone' => 'UTC',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PmrxAHXLcVwXkzFcbvpYR7AW0S157paX',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableSession' => true,
            'authTimeout' => 2592000,
            'enableAutoLogin' => false,
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => ['httponly' => true, 'lifetime' => 2592000],
            'timeout' => 2592000,
            'useCookies' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                // use temporary redirection instead of permanent for debugging
                'action' => yii\web\UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'register' => 'site/register',
                'login' => 'site/login',
                'logout' => 'site/logout',

                'queues/generate/<id:\d+>' => 'queues/generate',
                'queues/forward/<rid:\d+>/<token:\w+>' => 'queues/forward',
                'queues/monitor-socket/<lastLoadedId:\d+>/<firstLoadedId:\d+>' => 'queues/monitor-socket',

                'users/view/<id:\d+>' => 'users/view',
                'users/update/<id:\d+>' => 'users/update',
                'users/delete/<id:\d+>' => 'users/delete',

                'roles/view/<id:\d+>' => 'roles/view',
                'roles/update/<id:\d+>' => 'roles/update',
                'roles/delete/<id:\d+>' => 'roles/delete',

                'ads/delete/<name:.+>' => 'ads/delete',
            ],
        ],
       
    ],
    'params' => $params,
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
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
