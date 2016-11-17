<?php
/**
 * View model plugin
 *
 * @package Core_Controller_Plugin
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-03-03
 * @version 2016-11-17
 */

namespace Core\Controller\Plugin;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;
use Core\Loader\AutoLoader;
use Core\View\Model\ViewModelInterface;
use Core\View\Model\ViewModel as VModel;

/**
 * Get the view model from other method of controller
 */
class ViewModel extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * Saved view models
     *
     * @var array
     */
    protected $viewModels = [];

    /**
     * Get a model from the method of controller, it also can pass some parameters
     *
     * @param string|array $method
     * @param mixed $more_parameters
     * @return ViewModelInterface
     * @throws \InvalidArgumentException
     */
    public function __invoke($method)
    {
        $controller = $this->getController();
        $obj = null;

        /* @example $this->__invoke('listAction') */
        if (is_string($method)) {
            $obj = $controller;
            $fullControllerClassName = get_class($controller);
            $actionMethodName = $method;
        }
        elseif (is_array($method)) {
            /* @example $this->__invoke(['ProductController', 'listAction']) */
            if (is_string($method[0])) {
                $fullControllerClassName = AutoLoader::find("Controller_{$method[0]}");
                if (!$fullControllerClassName) {
                    throw new \InvalidArgumentException("Invalid controller class name: {$fullControllerClassName}");
                }
            }
            /* @example $this->__invoke([$this, 'listAction']) */
            else {
                $obj = $method[0];
                $fullControllerClassName = get_class($obj);
            }

            $actionMethodName = $method[1];
        }
        else {
            throw new \InvalidArgumentException("Invliad first parmaeter");
        }

        // called parameters
        $args = func_get_args();
        array_shift($args);

        // get the hash name of result
        $hash = $fullControllerClassName . '::' . $actionMethodName . ':' . ($args ? md5(serialize($args)) : '');

        // already exists
        if (array_key_exists($hash, $this->viewModels)) {
            return $this->viewModels[$hash];
        }

        // get $obj
        if (!$obj) {
            $obj = new $fullControllerClassName($this->locator);
        }

        // execute
        $result = call_user_func_array([$obj, $actionMethodName], $args);

        // don't use view
        if (false === $result) {
            $this->viewModels[$hash] = false;
            return false;
        }

        // define view model
        if ($result instanceof ViewModelInterface) {
            $model = $result;
        } else {
            $model = new VModel($result);
        }
        if (!$model->getTemplate()) {
            /* @var $front \Core\Controller\FrontController */
            $front = $this->locator->frontController;
            $template = $front->reflectControllerName($fullControllerClassName) . '/' . $front->reflectActionName($actionMethodName);
            $model->setTemplate($template);
        }

        // save & return
        return $this->models[$hash] = $model;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return ViewModel
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
