<?php
/**
 * Application initialization supporting file
 *
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-11
 * @version 2016-09-29
 */

use Core\Loader\AutoLoader;

/**
 * Filesystem constants
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR',  dirname(__DIR__) . DS);
define('CORE_DIR',  ROOT_DIR . 'Core' . DS);
define('APP_DIR',   ROOT_DIR . 'App' . DS);
define('WWW_DIR',   ROOT_DIR . 'Www' . DS);

/**
 * Register autoload
 */
include_once CORE_DIR . 'Loader' . DS . 'AutoLoader.php';
AutoLoader::setNamespaces([
    'Core' => CORE_DIR,
    'App' => APP_DIR . 'Class' . DS,
]);
AutoLoader::register();

define('MC_VERSION', '2.0');
