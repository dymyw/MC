<?php
/**
 * Str utils
 *
 * @package Core_Utils
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-21
 * @version 2017-10-18
 */

namespace Core\Utils;

class Str
{
    /**
     * @param string $str
     * @param string $seperator
     * @return string
     * @example
     *  $url .= Str::concat($query, '?');
     *  $sql .= Str::concat('LIMIT 0, 1');
     */
    public static function concat($str, $seperator = ' ')
    {
        if ('' === strval($str)) {
            return '';
        }

        return $seperator . $str;
    }
}
