<?php
/**
 * Min helper
 *
 * @package App_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-03
 */

namespace App\View\Helper;

class Min
{
    /**
     * Get smaller one
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    public function __invoke($a, $b)
    {
        return $a >= $b ? $b : $a;
    }
}
