<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// Define a path alias for the Bootstrap extension as it's used internally.
// In this example we assume that you unzipped the extension under protected/extensions.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

switch ($_SERVER['SERVER_NAME']) {
    case "olimp2014.net":
    case "www.olimp2014.net":
        $config = 'db/olimp2014.php';
        break;
    default:
        $config = 'db/development.php';
}
//Yii::app()->setHomeUrl('http://local.olimp2014.net/');

// склеиваем массив - основной и настройки БД
return CMap::mergeArray(
    require($config),
    array(
        // Языки для мультиязычности сайта
        'sourceLanguage' => 'en',
        'language' => 'ru',

        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'defaultController' => 'site',

        'name' => 'Зимние Олимпийские игры 2013 в Сочи',
        'theme' => 'olimp2014',

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
            'admin',
            'bugtracker',
            'rights',
            'user' => array(
                # encrypting method (php hash function)
                'hash' => 'md5',
                # send activation email
                'sendActivationMail' => false,
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
                    'tag'           => 'site/tag',
                    'tag/<url>'     => 'site/tag',
                    'post'          => 'site/post',
                    'post/<url>'    => 'site/post',
                    'place'         => 'site/place',
                    'place/<url>'   => 'site/place',
                    'sport'         => 'site/sport',
                    'sport/<url>'   => 'site/sport',
                    'category'      => 'site/category',
                    'category/<url>'=> 'site/category',

                    'date' => 'site/date',
                    'date/<y:\d+>' => 'site/date',
                    'date/<y:\d+>/<m:\d+>' => 'site/date',
                    'date/<y:\d+>/<m:\d+>/<d:\d+>' => 'site/date',

                    '<controller:\w+>/<id:\d+>' => '<controller>/view',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    /*
                    '<module:\w+><controller:\w+>/<id:\d+>' => '<module><controller>/view',
                    '<module:\w+><controller:\w+>/<action:\w+>' => '<module><controller>/<action>',
                    '<module:\w+><controller:\w+>/<action:\w+>/<id:\d+>' => '<module><controller>/<action>',
                    /**/
                ),
            ),
            'sms' => array(
                'class' => 'application.extensions.yii-sms.Sms',
                'login' => '9185569410', // Логин на сайте sms.ru
                'password' => 'SMS1pass', // Пароль
            ),
            'bootstrap' => array(
                'class' => 'ext.bootstrap.components.Bootstrap',
                'responsiveCss' => true,
            ),
            'errorHandler' => array(
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
            'adminEmail' => 'admin@olimp2014.net',
            'cdnPrefix' => '',
            //'cdnPrefix' => 'http://cdn.olimp2014.net',
        ),
    )
);