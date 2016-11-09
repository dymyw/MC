<?php

namespace App\Controller\Plugin;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;

class Func implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    public function getSum($a, $b)
    {
        return $a + $b + $this->locator->profile['age'];
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return Func
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
