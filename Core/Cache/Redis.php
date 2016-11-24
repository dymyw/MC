<?php
/**
 * Redis
 *
 * @package Core_Cache
 * @author Dymyw <dymayongwei@163.com>
 * @since 2016-11-24
 * @version 2016-11-24
 */

namespace Core\Cache;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;

class Redis extends \Redis implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator|\App\Hint\ServiceLocator
     */
    protected $locator = null;

    /**
     * Cache and get stored value (Redis cache)
     *
     * @param string $key
     * @param callable $generator
     * @param int $ttl
     * @return mixed
     */
    public function get($key, $generator, $ttl = 0)
    {
        if ($this->locator->has('redis') && $redis = $this->locator->get('redis')) {
            if ($redis->exists($key)) {
                return $redis->get($key);
            }

            $value = $generator();
            $redis->set($key, $value, $ttl);
            return $value;
        }

        return $generator();
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return Redis
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
