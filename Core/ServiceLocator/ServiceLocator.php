<?php
/**
 * ServiceLocator
 *
 * @package Core_ServiceLocator
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-13
 * @version 2016-09-29
 */

namespace Core\ServiceLocator;

/**
 * @property \Core\Db\Pdo $db The DB instance
 */
class ServiceLocator implements ServiceLocatorInterface
{
    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var array
     */
    protected $invokables = [];

    /**
     * @ver array
     */
    protected $aliases = [];

    /**
     * @ver array
     */
    protected $readonly = [];

    /**
     * @ver array
     */
    protected $parameters = [];

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!$config) {
            return;
        }

        // install services
        if (isset($config['services'])) {
            foreach ($config['services'] as $name => $value) {
                $this->setService($name, $value);
            }
        }

        // install invokables
        if (isset($config['invokables'])) {
            foreach ($config['invokables'] as $name => $invokable) {
                $this->setInvokable($name, $invokable);
            }
        }

        // set aliases
        if (isset($config['aliases'])) {
            foreach ($config['aliases'] as $alias => $name) {
                $this->setAlias($name, $alias);
            }
        }

        // set parameters
        if (isset($config['parameters'])) {
            foreach ($config['parameters'] as $name => $params) {
                $canonicalName = $this->getCanonicalName($name);
                if (isset($this->invokables[$canonicalName]) && is_array($params)) {
                    $this->parameters[$canonicalName] = $params;
                }
            }
        }

        // set readonly
        if (isset($config['readonly'])) {
            foreach ($config['readonly'] as $name => $readonly) {
                $canonicalName = $this->getCanonicalName($name);
                if ($canonicalName) {
                    $this->readonly[$canonicalName] = (bool) $readonly;
                }
            }
        }
    }

    /**
     * Set a service
     *
     * @param string $name
     * @param mixed $value
     * @param bool $readonly
     * @return ServiceLocator
     * @throws \InvalidArgumentException
     */
    public function setService($name, $value, $readonly = false)
    {
        if ($this->isReadonly($name)) {
            throw new \InvalidArgumentException(sprintf(
                'A service by the name or alias "%s" already exists and cannot be overridden; please use an alternate name', $name
            ));
        }

        $this->services[$name] = $value;
        $this->readonly[$name] = (bool) $readonly;

        return $this;
    }

    /**
     * Set an invokable class or callback
     *
     * @param string $name
     * @param string|callable $invokable
     * @param array|bool $params
     * @param bool $readonly
     * @return ServiceLocator
     * @throws \InvalidArgumentException
     */
    public function setInvokable($name, $invokable, $params = false, $readonly = false)
    {
        if ($this->isReadonly($name)) {
            throw new \InvalidArgumentException(sprintf(
                'A service by the name or alias "%s" already exists and cannot be overridden; please use an alternate name', $name
            ));
        }

        $this->invokables[$name] = $invokable;

        if (is_array($params)) {
            $this->parameters[$name] = $params;
            if ($readonly) {
                $this->readonly[$name] = true;
            }
        } elseif ($params) {
            $this->readonly[$name] = true;
        }

        return $this;
    }

    /**
     * Set an alias of server, every server can set some aliases
     *
     * @param string $name
     * @param string $alias
     * @return ServiceLocator
     * @example
     *  $locator->setAlias('name', 'n');    // $this->aliases['n'] => 'name'
     *  $locator->setAlias('n', 'me');      // $this->aliases['me'] => 'name'
     */
    public function setAlias($name, $alias)
    {
        $canonicalName = $this->getCanonicalName($name);
        if ($canonicalName) {
            $this->aliases[$alias] = $canonicalName;
        }

        return $this;
    }

    /**
     * Get a service
     *
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        $canonicalName = $this->getCanonicalName($name);

        // search from services
        if (array_key_exists($canonicalName, $this->services)) {
            return $this->services[$canonicalName];
        }

        // search from invokables
        if (isset($this->invokables[$canonicalName])) {
            $invokable = $this->invokables[$canonicalName];

            /**
             * @todo It's an invokable class
             */
            if (is_string($invokable) && class_exists($invokable)) {

            }
            /**
             * It's a callback function
             *
             * @example
             *  'db' => ['App\ServiceLocator\Invokable', 'getDbInstance'],
             */
            elseif (is_callable($invokable)) {
                $instance = $invokable($this);
                $this->invokables[$canonicalName] = $instance;
                return $instance;
            }
        }

        // directly invoke if exists
        if (class_exists($name)) {
            $this->setInvokable($name, $name);
            return $this->get($name);
        }

        // throw exception
        throw new \InvalidArgumentException(sprintf(
            'Attempt to get an invalid service: %s', $name
        ));
    }

    /**
     * Get a service
     *
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     * @see ServiceLocator::get($name)
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Whether or not has a service
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        $canonicalName = $this->getCanonicalName($name);
        return array_key_exists($canonicalName, $this->services) || isset($this->invokables[$canonicalName]);
    }

    /**
     * Get the canonical name of server
     *
     * @param string $name
     * @return string|null
     */
    protected function getCanonicalName($name)
    {
        if (array_key_exists($name, $this->aliases)) {
            return $this->aliases[$name];
        }

        if (array_key_exists($name, $this->services) || isset($this->invokables[$name])) {
            return $name;
        }

        return null;
    }

    /**
     * Whether or not is readonly
     *
     * @param string $name
     * @return bool
     */
    protected function isReadonly($name)
    {
        $canonicalName = $this->getCanonicalName($name);
        return $canonicalName && $this->readonly[$canonicalName];
    }
}
