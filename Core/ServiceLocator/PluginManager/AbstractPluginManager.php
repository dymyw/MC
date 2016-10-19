<?php
/**
 * AbstractPluginManager
 *
 * @package Core_ServiceLocator_PluginManager
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-12
 * @version 2016-10-19
 */

namespace Core\ServiceLocator\PluginManager;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;
use Core\ServiceLocator\InitializerInterface;

abstract class AbstractPluginManager implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * @var array
     */
    protected $plugins = [];

    /**
     * @var array
     */
    protected $callbacks = [];

    /**
     * Get the plugin class name
     *
     * @param string $name
     * @return string|false
     */
    abstract function getPluginClass($name);

    /**
     * Get the plugin instance
     *
     * @param string $name
     * @return object
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        // already exist
        if (array_key_exists($name, $this->plugins)) {
            $plugin = $this->plugins[$name];

            // implement InitializerInterface
            if ($plugin instanceof InitializerInterface) {
                $plugin->initialize();
            }

            return $plugin;
        }

        // check callbacks
        if (array_key_exists($name, $this->callbacks)) {
            $plugin = $this->callbacks[$name];

            // closure condition
            if (is_object($plugin) && $plugin instanceof \Closure) {
                $plugin = call_user_func($plugin);
            }
        }
        // check plugin
        else {
            // get the plugin class name
            $class = $this->getPluginClass($name);

            // not exists
            if (!$class) {
                throw new \InvalidArgumentException("Invalid plugin name: {$name}");
            }

            // instance
            $plugin = new $class;
        }

        // set service locator
        if ($plugin instanceof ServiceLocatorAwareInterface) {
            $plugin->setServiceLocator($this->locator);
        }

        // factory
        if ($plugin instanceof FactoryInterface) {
            $plugin = $plugin->factory();
        }

        // save
        $this->plugins[$name] = $plugin;

        // return
        return $plugin;
    }

    /**
     * Get the plugin instance
     *
     * @param string $name
     * @return object
     * @throws \InvalidArgumentException
     * @see AbstractPluginManager::get($name)
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Overloading: proxy to plugins
     *
     * If the plugin does not define __invoke, it will be return
     * If the plugin does define __invoke, it will be called as a functor
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $plugin = $this->get($name);

        // callable
        if (is_callable($plugin)) {
            return call_user_func_array($plugin, $arguments);
        }

        // return
        return $plugin;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return AbstractPluginManager
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
