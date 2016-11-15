<?php
/**
 * String utils
 *
 * @package Core_Utils
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-21
 * @version 2016-11-15
 */

namespace Core\Utils;

class String
{
    /**
     * @param string $str
     * @param string $seperator
     * @return string
     * @example
     *  $url .= String::concat($query, '?');
     *  $sql .= String::concat('LIMIT 0, 1');
     */
    public static function concat($str, $seperator = ' ')
    {
        if ('' === strval($str)) {
            return '';
        }

        return $seperator . $str;
    }
}
