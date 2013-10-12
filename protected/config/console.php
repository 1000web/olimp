<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

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
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => 'My Console Application',

        // preloading 'log' component
        'preload' => array('log'),
        'modules' => array(
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
            ),
        ),
        // application components
        'components' => array(
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
                ),
            ),
        ),
    )
);