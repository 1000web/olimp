<?php

// change the following paths if necessary
$yii = dirname(__FILE__) . '/framework/yii.php';

switch (substr($_SERVER['SERVER_NAME'],0,6)) {
    case "local.":
        // remove the following lines when in production mode
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        break;
}
$config = dirname(__FILE__) . '/protected/config/main.php';

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
Yii::createWebApplication($config)->run();
