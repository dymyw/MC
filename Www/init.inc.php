<?php
/**
 * Application initialization supporting file
 *
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-11
 * @version 2016-11-02
 */

use Core\Loader\AutoLoader;
use Core\ServiceLocator\ServiceLocator;

/**
 * Filesystem constants
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR',  dirname(__DIR__) . DS);
define('CORE_DIR',  ROOT_DIR . 'Core' . DS);

define('APP_DIR',       ROOT_DIR . 'App' . DS);
define('CONFIG_DIR',    APP_DIR . 'Config' . DS);
define('TPL_DIR',       APP_DIR . 'Template' . DS);

define('WWW_DIR',   ROOT_DIR . 'Www' . DS);

/**
 * The base path of URLs
 */
!defined('BASE_PATH') && define('BASE_PATH', '/');

/**
 * Load the private configure
 */
if (file_exists(CONFIG_DIR . 'Config.private.php')) {
    include CONFIG_DIR . 'Config.private.php';
}

/**
 * Register autoload
 */
include_once CORE_DIR . 'Loader' . DS . 'AutoLoader.php';
AutoLoader::setNamespaces([
    'Core' => CORE_DIR,
    'App' => APP_DIR . 'Class' . DS,
]);
AutoLoader::register();

/**
 * Database information
 */
!defined('DB_HOST') && define('DB_HOST', 'localhost');
!defined('DB_PORT') && define('DB_PORT', 3306);
!defined('DB_USERNAME') && define('DB_USERNAME', 'root');
!defined('DB_PASSWORD') && define('DB_PASSWORD', '');
!defined('DB_DATABASE') && define('DB_DATABASE', 'test');

/* @var $locator ServiceLocator */
$GLOBALS['locator'] = new ServiceLocator(include CONFIG_DIR . 'Service.php');

define('MC_VERSION', '2.0');
