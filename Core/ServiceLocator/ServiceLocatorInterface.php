<?php
/**
 * ServiceLocator interface
 *
 * @package Core_ServiceLocator
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-13
 * @version 2016-09-29
 */

namespace Core\ServiceLocator;

interface ServiceLocatorInterface
{
    /**
     * Set a service
     *
     * @param string $name
     * @param mixed $value
     * @param bool $readonly
     * @return ServiceLocatorInterface
     * @throws \InvalidArgumentException
     */
    public function setService($name, $value, $readonly = false);

    /**
     * Set an invokable class or callback
     *
     * @param string $name
     * @param string|callable $invokable
     * @param array|bool $params
     * @param bool $readonly
     * @return ServiceLocatorInterface
     * @throws \InvalidArgumentException
     */
    public function setInvokable($name, $invokable, $params = false, $readonly = false);

    /**
     * Get a service
     *
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($name);

    /**
     * Whether or not has a service
     *
     * @param string $name
     * @return bool
     */
    public function has($name);
}
