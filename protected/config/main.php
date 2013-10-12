<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// Define a path alias for the Bootstrap extension as it's used internally.
// In this example we assume that you unzipped the extension under protected/extensions.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

switch (substr($_SERVER['SERVER_NAME'], 0, 6)) {
    // если имя сервера начинается на local.
    case "local.":
        $config = 'development.php';
        break;
    default:
        $config = 'production.php';
}
// склеиваем массив - основной и настройки БД
return CMap::mergeArray(
    require($config),
    array(
        // Языки для мультиязычности сайта
        'sourceLanguage' => 'en',
        'language' => 'ru',

        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => 'CRM.1000web',
        //'theme'=>'bootstrap', // requires you to copy the theme under your themes directory

        // preloading 'log' component
        'preload' => array(
            'log',
            'bootstrap',
        ),
        // autoloading model and component classes
        'import' => array(
            'application.models.*',
            'application.components.*',
            'application.modules.user.*',
            'application.modules.user.models.*',
            'application.modules.user.components.*',
            'application.modules.rights.*',
            'application.modules.rights.models.*',
            'application.modules.rights.components.*',
        ),
        'modules' => array(
            'rights',
            'user' => array(
                # encrypting method (php hash function)
                'hash' => 'md5',
                # send activation email
                'sendActivationMail' => true,
                # allow access for non-activated users
                'loginNotActiv' => false,
                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => true,
                # automatically login from registration
                'autoLogin' => true,
                # registration path
                'registrationUrl' => array('/user/registration'),
                # recovery password path
                'recoveryUrl' => array('/user/recovery'),
                # login form path
                'loginUrl' => array('/user/login'),
                # page after login
                'returnUrl' => array('/user/profile'),
                # page after logout
                'returnLogoutUrl' => array('/user/login'),

                // названия таблиц взяты по умолчанию, их можно изменить
                'tableUsers' => 'tbl_users',
                'tableProfiles' => 'tbl_profiles',
                'tableProfileFields' => 'tbl_profiles_fields',
            ),
            // uncomment the following to enable the Gii tool
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => 'gii',
                'generatorPaths' => array(
                    'bootstrap.gii'
                ),
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => array('127.0.0.1', '::1'),
            ),
        ),

        // application components
        'components' => array(
            'user' => array(
                'class' => 'RWebUser',
                'allowAutoLogin' => true,
            ),
            'authManager' => array(
                'class' => 'RDbAuthManager',
                'defaultRoles' => array('Guest') // дефолтная роль
            ),
            // uncomment the following to enable URLs in path-format
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'caseSensitive' => false,
                'urlSuffix' => '',
                'rules' => array(
                    '<controller:\w+>/<id:\d+>' => '<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                ),
            ),
            'sms' => array
            (
                'class' => 'application.extensions.yii-sms.Sms',
                'login' => '9185569410', // Логин на сайте sms.ru
                'password' => 'SMS1pass', // Пароль
            ),
            'bootstrap' => array(
                'class' => 'ext.bootstrap.components.Bootstrap',
                'responsiveCss' => true,
            ),
            'errorHandler' => array(
                // use 'site/error' action to display errors
                'errorAction' => 'site/error',
            ),
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
                    // uncomment the following to show log messages on web pages
                    /*
                    array(
                        'class'=>'CWebLogRoute',
                    ),
                    /**/
                ),
            ),
        ),

        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        'params' => array(
            // this is used in contact page
            'adminEmail' => 'admin@1000web.ru',
        ),
    )
);