<?php
/**
 * Action helper
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-28
 * @version 2016-11-24
 */

namespace Core\View\Helper;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;

class Action implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator|\App\Hint\ServiceLocator
     */
    protected $locator = null;

    /**
     * Get action name
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->locator->actionName;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return Action
     */
    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }
}
