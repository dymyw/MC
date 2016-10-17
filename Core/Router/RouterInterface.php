<?php
/**
 * Router interface
 *
 * @package Core_Router
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-15
 * @version 2016-10-12
 */

namespace Core\Router;

interface RouterInterface
{
    /**
     * Parse URL
     *
     * @param string $requestUrl
     * @return array
     */
    public function parseUrl($requestUrl = null);

    /**
     * Create URL
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function createUrl($path, array $params);
}
