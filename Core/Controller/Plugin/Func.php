<?php
/**
 * Func plugin
 *
 * @package Core_Controller_Plugin
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-02-04
 * @version 2016-11-16
 */

namespace Core\Controller\Plugin;

class Func
{
    /**
     * Save log
     *
     * @param string $filename
     * @param mix $data
     * @param bool $override
     */
    public function saveLog($filename, $data, $override = false)
    {
        if ($override) {
            file_put_contents($filename, $data);
        } else {
            file_put_contents($filename, date("c") . "\t" . $data . "\n", FILE_APPEND);
        }
    }
}
