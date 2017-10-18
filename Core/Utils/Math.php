<?php
/**
 * Math utils
 *
 * @package Core_Utils
 * @author Dymyw <dymayongwei@163.com>
 * @since 2016-11-24
 * @version 2017-10-18
 */

namespace Core\Utils;

use Core\Utils\Str;

class Math
{
    /**
     * Format a number as byte, based on size
     *
     * @param int|string $num
     * @param int $precision
     * @return string
     */
    public static function byteFormat($num, $precision = 1)
    {
        if ($num > 1000000000000) {
            $num = round($num / 1099511627776, $precision);
            $unit = 'TB';
        }
        elseif ($num > 1000000000) {
            $num = round($num / 1073741824, $precision);
            $unit = 'GB';
        }
        elseif ($num > 1000000) {
            $num = round($num / 1048576, $precision);
            $unit = 'MB';
        }
        elseif ($num > 1000) {
            $num = round($num / 1024, $precision);
            $unit = 'KB';
        }
        else {
            return number_format($num) . Str::concat('Bytes');
        }

        return number_format($num, $precision) . Str::concat($unit);
    }
}
