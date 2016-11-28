<?php
/**
 * ServiceLocator
 *
 * @package Core_ServiceLocator
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-13
 * @version 2016-11-28
 */

namespace Core\ServiceLocator;

use Core\Utils\Reflection;

/**
 * @property string $MC_VERSION
 *
 * @property \Core\Db\Pdo $db The DB instance
 * @property \Core\Router\Router $router The Router instance
 * @property \Core\Controller\FrontController $frontController The front controller
 * @property \Core\Controller\AbstractActionController $controller The current controller instance
 *
 * @property string $controllerName The controller name
 * @property string $actionName The action name
 */
class ServiceLocator implements ServiceLocatorInterface
{
    /**
     * @var array
     */
    protected $services = [
        'MC_VERSION' => '2.0',
    ];

    /**
     * @var array
     */
    protected $invokables = [];

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @var array
     */
    protected $readonly = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * Constructor
     *
     * @param array|ArrayAccess $config
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
     * @example
     *  $this->setInvokable('db', 'Core\Db\Pdo', ['dsn' => 'xxx', 'username' => 'xxx', 'password' => 'xxx']);
     *  $this->setInvokable('db', 'App\Db', true);
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
        // get the canonical name
        $canonicalName = $this->getCanonicalName($name);

        // search from services
        if (array_key_exists($canonicalName, $this->services)) {
            return $this->services[$canonicalName];
        }

        // search from invokables
        if (isset($this->invokables[$canonicalName])) {
            $invokable = $this->invokables[$canonicalName];

            // It's an invokable class
            if (is_string($invokable) && class_exists($invokable)) {
                $params = $this->getInvokableParams($canonicalName);
                $instance = $params ? Reflection::newInstance($invokable, $params) : new $invokable;

                if ($instance instanceof ServiceLocatorAwareInterface) {
                    $instance->setServiceLocator($this);
                }

                $this->services[$canonicalName] = $instance;
                return $instance;
            }
            /**
             * It's a callback function
             *
             * @example
             *  'db' => ['App\ServiceLocator\Invokable', 'getDbInstance'],
             */
            elseif (is_callable($invokable)) {
                $instance = $invokable($this);
                $this->services[$canonicalName] = $instance;
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
     * Get the params of invokable class/callback
     *
     * @param string $name
     * @return array
     */
    protected function getInvokableParams($name)
    {
        $canonicalName = $this->getCanonicalName($name);
        return isset($this->parameters[$canonicalName]) ? $this->parameters[$canonicalName] : [];
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
     * Get the canonical name
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
        return $canonicalName && !empty($this->readonly[$canonicalName]);
    }
}
