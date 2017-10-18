<?php
/**
 * Url utils
 *
 * @package Core_Utils
 * @author Dymyw <dymayongwei@163.com>
 * @since 2016-11-23
 * @version 2017-10-18
 */

namespace Core\Utils;

use Core\Utils\Str;

class Url
{
    /**
     * Add query to url
     *
     * @param string|array|null $query
     * @param string|null $url
     * @return string
     * @example
     *  Url::buildUrl('id=2&type=test', '/example.php?id=1&name=test');
     *  Url::buildUrl(['id' => 2, 'type' => 'test'], '/example.php?id=1&name=test');
     *      => /example.php?id=2&name=test&type=test
     */
    public static function buildUrl($query = null, $url = null)
    {
        // the default url is REQUEST_URI
        if (null === $url) {
            $url = $_SERVER['REQUEST_URI'];
        }

        // no new query
        if (!$query) {
            return $url;
        }

        // get the position of query mark
        $markPos = strpos($url, '?');

        // get the new query array
        if (is_string($query)) {
            parse_str($query, $newQueryArray);
        } else {
            $newQueryArray = $query;
        }

        // merge the new query to the old query
        if (false === $markPos) {
            $path = $url;
        } else {
            $path = substr($url, 0, $markPos);
            parse_str(substr($url, $markPos + 1), $oldQueryArray);
            $newQueryArray = array_merge($oldQueryArray, $newQueryArray);
        }

        // concat the query to path
        return $path . Str::concat(http_build_query($newQueryArray), '?');
    }
}
