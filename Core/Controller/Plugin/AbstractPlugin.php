<?php
/**
 * AbstractPlugin
 *
 * @package Core_Controller_Plugin
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-11-15
 */

namespace Core\Controller\Plugin;

use Core\Controller\AbstractActionController;

abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var AbstractActionController
     */
    protected $controller = null;

    /**
     * Set controller
     *
     * @param AbstractActionController $controller
     * @return AbstractPlugin
     */
    public function setController(AbstractActionController $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Get controller
     *
     * @return AbstractActionController
     */
    public function getController()
    {
        return $this->controller;
    }
}
