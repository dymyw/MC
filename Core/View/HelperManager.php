<?php
/**
 * HelperManager
 *
 * @package Core_View
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-14
 */

namespace Core\View;

use Core\ServiceLocator\PluginManager\AbstractPluginManager;
use Core\Loader\AutoLoader;

/**
 * @property \Core\View\Helper\Url $url
 *
 * @method \Core\View\Helper\Url url($path = 'default/index', $params = [], $https = false, $forceHost = false)
 * @method \Core\View\Helper\SelfUrl selfUrl($query = null, $escape = true) Get current url
 * @method \Core\View\Helper\Controller controller() Controller name
 * @method \Core\View\Helper\Action action() Action name
 * @method \Core\View\Helper\PageId pageId() Controller + Action
 * @method \Core\View\Helper\Escape escape($str) Escape the html string
 * @method \Core\View\Helper\Date date($date, $format = 'Y-m-d H:i:s', $default = null) Format date
 */
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
