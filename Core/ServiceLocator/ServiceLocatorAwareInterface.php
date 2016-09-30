<?php
/**
 * ServiceLocatorAware interface
 *
 * @package Core_ServiceLocator
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-15
 * @version 2016-09-30
 */

namespace Core\ServiceLocator;

interface ServiceLocatorAwareInterface
{
    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator);

    /**
     * Get service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator();
}
