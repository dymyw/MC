<?php
/**
 * Escape helper
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-30
 * @version 2016-11-14
 */

namespace Core\View\Helper;

class Escape
{
    /**
     * Escape the html string
     *
     * @param string $str
     * @return string
     */
    public function __invoke($str)
    {
        return htmlspecialchars($str, ENT_COMPAT | ENT_XHTML);
    }
}
