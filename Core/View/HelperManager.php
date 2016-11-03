<?php
/**
 * HelperManager
 *
 * @package Core_View
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-03
 */

namespace Core\View;

use Core\ServiceLocator\PluginManager\AbstractPluginManager;
use Core\Loader\AutoLoader;

class HelperManager extends AbstractPluginManager
{
    /**
     * Get the plugin class name
     *
     * @param string $name
     * @return string|bool
     */
    public function getPluginClass($name)
    {
        $name = ucfirst($name);
        $class = AutoLoader::find("View_Helper_{$name}");
        return $class ?: false;
    }
}
