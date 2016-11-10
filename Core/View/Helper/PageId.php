<?php
/**
 * PageId helper
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-28
 * @version 2016-11-10
 */

namespace Core\View\Helper;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;

class PageId implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * @var string
     */
    protected $pageId = null;

    /**
     * Get page id
     *
     * @return string
     */
    public function __invoke()
    {
        if (null === $this->pageId) {
            return $this->pageId = $this->locator->controllerName . '-' . $this->locator->actionName;
        }

        return $this->pageId;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return PageId
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
