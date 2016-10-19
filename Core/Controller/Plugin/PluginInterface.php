<?php
/**
 * Plugin interface
 *
 * @package Core_Controller_Plugin
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-10-19
 */

namespace Core\Controller\Plugin;

use Core\Controller\AbstractActionController;

interface PluginInterface
{
    /**
     * Set controller
     *
     * @param AbstractActionController $controller
     * @return PluginInterface
     */
    public function setController(AbstractActionController $controller);

    /**
     * Get controller
     *
     * @return AbstractActionController
     */
    public function getController();
}
