<?php

namespace App\Controller;

use Core\ServiceLocator\ServiceLocator;
use Core\ServiceLocator\ServiceLocatorAwareInterface;

class Front implements ServiceLocatorAwareInterface
{
    public $name;
    private $age;

    /**
     * @var ServiceLocator
     */
    protected $locator;

    public function __construct($name = 'dymyw', $age = 28)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function getInfo()
    {
        return $this->name . ': ' . $this->age;
    }

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
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }
}
