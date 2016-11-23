<?php
/**
 * Self url helper
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2016-11-23
 * @version 2016-11-23
 */

namespace Core\View\Helper;

use Core\Utils\Url as UtilsUrl;

class SelfUrl
{
    /**
     * Get current url, it's almost same with $_SERVER['REQUEST_URI']
     *
     * @param string|array|null $query
     * @param bool $escape
     * @return string
     */
    public function __invoke($query = null, $escape = true)
    {
        $url = UtilsUrl::buildUrl($query, $_SERVER['REQUEST_URI']);
        return $escape ? htmlspecialchars($url) : $url;
    }
}
