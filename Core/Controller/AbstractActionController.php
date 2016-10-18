<?php
/**
 * AbstractActionController
 *
 * @package Core_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-05
 * @version 2016-10-18
 */

namespace Core\Controller;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;

abstract class AbstractActionController implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator;

    /**
     * Constructor
     *
     * @param ServiceLocator $locator
     */
    public function __construct(ServiceLocator $locator)
    {
        // set service locator
        $this->setServiceLocator($locator);

        // initialize
        $this->init();
    }

    /**
     * Triggered by {@link __construct() the constructor} as its final action
     *
     * @return void
     */
    public function init()
    {}

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @throws \BadMethodCallException
     */
    public function getServiceLocator()
    {
        throw new \BadMethodCallException("It can't be invoked by outer class.");
    }
}
