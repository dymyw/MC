<?php
/**
 * Date helper
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-02-02
 * @version 2016-11-14
 */

namespace Core\View\Helper;

class Date
{
    /**
     * Format date
     *
     * @param string|int $date
     * @param string $format
     * @param string $default
     * @return string
     */
    public function __invoke($date, $format = 'Y-m-d H:i:s', $default = null)
    {
        if (empty($date) || '0000-00-00' === $date || '0000-00-00 00:00:00' === $date) {
            return $default;
        }

        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return date($format, $timestamp);
    }
}
