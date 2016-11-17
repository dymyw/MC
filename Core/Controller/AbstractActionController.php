<?php
/**
 * AbstractActionController
 *
 * @package Core_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-05
 * @version 2016-11-17
 */

namespace Core\Controller;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;
use Core\Controller\Plugin\PluginInterface;
use Core\View\Model\ViewModelInterface;

/**
 * @property \Core\Model\ModelManager|\App\Hint\ModelManager $models The model manager
 * @property \Core\View\HelperManager|\App\Hint\HelperManager $helpers The helper manager
 *
 * @property \Core\Controller\Plugin\Layout $layout The layout plugin
 * @property \Core\View\View $view The view plugin
 * @property \Core\Controller\Plugin\Param $param Get the parameter value plugin
 * @property \Core\Controller\Plugin\Func $func The misc functions
 *
 * @method \Core\Controller\Plugin\Layout layout($model) The layout plugin
 * @method \Core\View\View view() The view plugin
 * @method \Core\Controller\Plugin\Param param(string $name, $default) Get the parameter value
 * @method \Core\Controller\Plugin\ViewModel viewModel($method) Get the view model from other method of controller
 */
abstract class AbstractActionController implements ServiceLocatorAwareInterface
{
    /**
     * Save the layout model, also {@see Plugin\Layout}
     *
     * @var ViewModelInterface
     */
    protected $layout = null;

    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * Constructor
     *
     * @param ServiceLocator $locator
     */
    public function __construct(ServiceLocator $locator)
    {
        // set service locator
        $this->setServiceLocator($locator);

        // register view helpers
        $this->helpers->register('param', function() {
            return $this->plugin('param');
        });

        // initialize
        $this->init();
    }

    /**
     * Triggered by {@link __construct() the constructor} as its final action
     *
     * @return void
     */
    public function init()
    {}

    /**
     * Set layout
     *
     * @param ViewModelInterface $model
     * @return AbstractActionController
     * @see Plugin\Layout
     */
    public function setLayout(ViewModelInterface $model = null)
    {
        $this->layout = $model;
        return $this;
    }

    /**
     * Get layout
     *
     * @return ViewModelInterface
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Get plugin instance
     *
     * @param string $name
     * @return object
     */
    public function plugin($name)
    {
        /* @var $plugins PluginManager */
        $plugins = $this->locator->get('Core\Controller\PluginManager');

        $plugin = $plugins->get($name);
        // set controller for plugin which is implement PluginInterface
        if ($plugin instanceof PluginInterface) {
            $plugin->setController($this);
        }

        return $plugin;
    }

    /**
     * You can invoke the controller plugin
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        // model manager
        if ('models' === $name) {
            return $this->locator->get('Core\Model\ModelManager');
        }

        // view helper manager
        if ('helpers' === $name) {
            return $this->locator->get('Core\View\HelperManager');
        }

        // controller plugin instance
        return $this->plugin($name);
    }

    /**
     * Invoke the controller plugin
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @see PluginManager
     */
    public function __call($name, $arguments)
    {
        // ensure set controller
        $this->plugin($name);

        /* @var $plugins PluginManager */
        $plugins = $this->locator->get('Core\Controller\PluginManager');
        return $plugins->__call($name, $arguments);
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return AbstractActionController
     */
    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @throws \BadMethodCallException
     */
    public function getServiceLocator()
    {
        throw new \BadMethodCallException("It can't be invoked by outer class.");
    }
}
