<?php
/**
 * AbstractModel
 *
 * @package Core_Model
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-09
 */

namespace Core\Model;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;

/**
 * @property \Core\Db\Pdo $db The DB instance
 * @property \Core\Model\ModelManager $models the data model manager
 */
class AbstractModel implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        if ('db' === $name) {
            return $this->locator->get('db');
        }

        if ('models' === $name) {
            return $this->locator->get('Core\Model\ModelManager');
        }

        throw new \InvalidArgumentException("Invalid property: {$name}");
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return AbstractModel
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
