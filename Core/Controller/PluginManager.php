<?php
/**
 * PluginManager
 *
 * @package Core_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-05
 * @version 2016-10-19
 */

namespace Core\Controller;

use Core\ServiceLocator\PluginManager\AbstractPluginManager;
use Core\Loader\AutoLoader;

class PluginManager extends AbstractPluginManager
{
    /**
     * Get the plugin class name
     *
     * @param string $name
     * @return string|false
     */
    public function getPluginClass($name)
    {
        $name = ucfirst($name);
        $class = AutoLoader::find("Controller_Plugin_{$name}");
        return $class ?: false;
    }
}
