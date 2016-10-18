<?php
/**
 * FrontController
 *
 * @package Core_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-14
 * @version 2016-10-18
 */

namespace Core\Controller;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;
use Core\Utils\WordConvertor;
use Core\Loader\AutoLoader;

class FrontController implements ServiceLocatorAwareInterface
{
    /**
     * The default controller name
     *
     * @var string
     */
    protected $defaultControllerName = 'default';

    /**
     * The default action name
     *
     * @var string
     */
    protected $defaultActionName = 'index';

    /**
     * The default not found action name
     *
     * @var type
     */
    protected $notFoundActionName = 'not-found';

    /**
     * Current controller name
     *
     * @var string
     */
    protected $controllerName = null;

    /**
     * Current action name
     *
     * @var string
     */
    protected $actionName = null;

    /**
     * Current controller object
     *
     * @var AbstractActionController
     */
    protected $controller = null;

    /**
     * @var ServiceLocator
     */
    protected $locator;

    /**
     * Set the default controller name
     *
     * @param string $name
     * @return FrontController
     */
    public function setDefaultControllerName($name)
    {
        $this->defaultControllerName = $name;
        return $this;
    }

    /**
     * Get the default controller name
     *
     * @return string
     */
    public function getDefaultControllerName()
    {
        return $this->defaultControllerName;
    }

    /**
     * Set the default action name
     *
     * @param string $name
     * @return FrontController
     */
    public function setDefaultActionName($name)
    {
        $this->defaultActionName = $name;
        return $this;
    }

    /**
     * Get the default action name
     *
     * @return string
     */
    public function getDefaultActionName()
    {
        return $this->defaultActionName;
    }

    /**
     * Set the default not found action name
     *
     * @param string $name
     * @return FrontController
     */
    public function setNotFoundActionName($name)
    {
        $this->notFoundActionName = $name;
        return $this;
    }

    /**
     * Get the default not found action name
     *
     * @return string
     */
    public function getNotFoundActionName()
    {
        return $this->notFoundActionName;
    }

    /**
     * Get the controller name
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * Get the action name
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Dispatch
     *
     * @param string $controllerName
     * @param string $actionName
     * @return mixed|false
     */
    public function dispatch($controllerName = null, $actionName = null)
    {
        $controllerClassName = $this->formatControllerName($controllerName);
        $actionMethodName = $this->formatActionName($actionName);
        $result = $this->find($controllerClassName, $actionMethodName);
        return $result;
    }

    /**
     * Format a controller name
     *
     * @param string $controllerName
     * @return string
     * @example default-name => DefaultNameController
     */
    public function formatControllerName($controllerName)
    {
        if (empty($controllerName)) {
            return null;
        }

        $controllerName = strtolower($controllerName);
        return WordConvertor::dashToCamelCase($controllerName) . 'Controller';
    }

    /**
     * Format a action name
     *
     * @param string $actionName
     * @return string
     * @example action-name => actionNameAction
     */
    public function formatActionName($actionName)
    {
        if (empty($actionName)) {
            return null;
        }

        $actionName = strtolower($actionName);
        return WordConvertor::dashToCamelCase($actionName, true) . 'Action';
    }

    /**
     * Reflect controller name
     *
     * $formtedName can be the full controller name
     *
     * @param string $formatedName
     * @return string
     * @example App\Controller\DefaultNameController => default-name
     */
    public function reflectControllerName($formatedName)
    {
        $formatedName = preg_replace('/^.*(\w+)Controller$/U', '\1', $formatedName);
        $name = WordConvertor::camelCaseToDash($formatedName);
        return $name;
    }

    /**
     * Reflect action name
     *
     * @param stringe $formatedName
     * @return string
     * @example actionNameAction => action-name
     */
    public function reflectActionName($formatedName)
    {
        $formatedName = preg_replace('/Action$/', '', $formatedName);
        $name = WordConvertor::camelCaseToDash($formatedName);
        return $name;
    }

    /**
     * Find the right action of the right controller
     *  1. controller/action
     *  2. controller/not-found
     *  3. default/not-found
     *  4. default/index
     *
     * @param string $controllerClassName
     * @param string $actionMethodName
     * @return mixed|false
     * @throws \RuntimeException
     */
    protected function find($controllerClassName, $actionMethodName)
    {
        $fullControllerClassName = AutoLoader::find("Controller_{$controllerClassName}");

        // don't exist the controller
        if (!$fullControllerClassName) {
            $defaultControllerClassName = $this->formatControllerName($this->getDefaultControllerName());

            // already the default controller name
            if ($defaultControllerClassName == $controllerClassName) {
                throw new \RuntimeException("Invalid default controller class name: {$controllerClassName}");
            }

            // find next
            $notFoundActionMethodName = $this->formatActionName($this->getNotFoundActionName());
            return $this->find($defaultControllerClassName, $notFoundActionMethodName);
        }

        // exist the controller, but don't exist the action
        if (!method_exists($fullControllerClassName, $actionMethodName)) {
            $notFoundActionMethodName = $this->formatActionName($this->getNotFoundActionName());

            // already the notfound action
            if ($actionMethodName == $notFoundActionMethodName) {
                // use the notfound action of default controller
                $defaultControllerClassName = $this->formatControllerName($this->getDefaultControllerName());

                // already the default controller name
                if ($defaultControllerClassName == $controllerClassName) {
                    // default action method name
                    $defaultActionMethodName = $this->formatActionName($this->defaultActionName);

                    // aready the default action name
                    if (!method_exists($fullControllerClassName, $defaultActionMethodName)) {
                        throw new \RuntimeException("Invalid default action: {$fullControllerClassName}::{$defaultActionMethodName}");
                    }

                    // aready the default action name
                    if ($actionMethodName == $defaultActionMethodName) {
                        throw new \RuntimeException("Invalid default action method name: {$actionMethodName}");
                    }

                    // find next
                    return $this->find($defaultControllerClassName, $defaultActionMethodName);
                }

                // find next
                return $this->find($defaultControllerClassName, $notFoundActionMethodName);
            }

            // find next
            return $this->find($controllerClassName, $notFoundActionMethodName);
        }

        // exist the controller and exist the action
        $this->controllerName = $this->reflectControllerName($fullControllerClassName);
        $this->actionName = $this->reflectActionName($actionMethodName);

        // set service
        if ($this->locator) {
            $this->locator->setService('controllerName', $this->controllerName);
            $this->locator->setService('actionName', $this->actionName);
        }

        /* @var $controller \Core\Controller\AbstractActionController */
        $controller = new $fullControllerClassName($this->getServiceLocator());
        if ($this->locator) {
            $this->locator->setService('controller', $controller);
        }

        // set the controller object
        $this->controller = $controller;

        // get result
        $result = $controller->$actionMethodName();

        // return
        return $result;
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
