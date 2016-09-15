<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            'class' => \dektrium\user\Module::className(),
            'enableUnconfirmedLogin' => true,
            'enableFlashMessages' => false,
            // 'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['admin'],
            'controllerMap' => [
                'registration' => [
                    'class' => \dektrium\user\controllers\RegistrationController::className(),
                    'on ' . \dektrium\user\controllers\RegistrationController::EVENT_AFTER_REGISTER => function ($e) {
                        Yii::$app->response->redirect(array('/user/security/login'))->send();
                        Yii::$app->end();
                    }
                ],
            ],
        ],
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        // 'user' => [
        //     'identityClass' => 'common\models\User',
        //     'enableAutoLogin' => true,
        // ],
        'authClientCollection' => [
            'class' => yii\authclient\Collection::className(),
            'clients' => [
                'facebook' => [
                    'class'        => 'dektrium\user\clients\Facebook',
                    'clientId'     => '474327099373950',
                    'clientSecret' => '9917a1e5591b8e1cda02e22d89f9cd9f',
                    'viewOptions' => ['popupWidth' => 1024, 'popupHeight' => 860,]  
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@frontend/views/user'
                ],
            ],
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
        'request'=>[
            'class' => 'common\components\Request',
            'web'=> '/frontend/web'
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true, 
            /**
             * /user/registration/register Displays registration form
             * /user/registration/resend Displays resend form
             * /user/registration/confirm Confirms a user (requires id and token query params)
             * /user/security/login Displays login form
             * /user/security/logout Logs the user out (available only via POST method)
             * /user/recovery/request Displays recovery request form
             * /user/recovery/reset Displays password reset form (requires id and token query params)
             * /user/settings/profile Displays profile settings form
             * /user/settings/account Displays account settings form (email, username, password)
             * /user/settings/networks Displays social network accounts settings page
             * /user/profile/show Displays user's profile (requires id query param)
             * /user/admin/index Displays user management interface
             */
            'rules' => [
                '/user/register' => '/user/registration/register',
                '/user/resend' => '/user/registration/resend',
                '/user/forgot' => '/user/recovery/request',
                '/user/login' => '/user/security/login',
                '/user/logout' => '/user/security/logout',

                '<controller:\w+>/<id:\d+>/<name:.+>' => '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
