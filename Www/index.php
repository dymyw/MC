<?php
/**
 * Application entrance
 *
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-11
 * @version 2016-09-29
 */

include_once 'init.inc.php';

// Core\Loader\AutoLoader::load
use App\Temp\NsTemp;
new NsTemp;

new Temp();

// Core\Loader\AutoLoader::find
$nsObjName = Core\Loader\AutoLoader::find('Temp/NsTemp');
new $nsObjName;
