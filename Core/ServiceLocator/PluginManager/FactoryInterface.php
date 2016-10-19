<?php
/**
 * Factory interface
 *
 * @package Core_ServiceLocator_PluginManager
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-10-19
 */

namespace Core\ServiceLocator\PluginManager;

interface FactoryInterface
{
    /**
     * @return object
     */
    public function factory();
}
