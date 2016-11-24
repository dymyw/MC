<?php
/**
 * View plugin
 *
 * @package Core_Controller_Plugin
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-11-24
 */

namespace Core\Controller\Plugin;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;
use Core\ServiceLocator\PluginManager\FactoryInterface;

class View extends AbstractPlugin implements ServiceLocatorAwareInterface, FactoryInterface
{
    /**
     * @var ServiceLocator|\App\Hint\ServiceLocator
     */
    protected $locator = null;

    /**
     * Get View object
     *
     * @return \Core\View\View
     */
    public function factory()
    {
        /* @var $resolver \Core\View\Resolver\Resolver */
        $resolver = $this->locator->get('Core\View\Resolver\Resolver');
        $resolver->addPath(TPL_DIR);

        /* @var $renderer \Core\View\Renderer\PhpRenderer */
        $renderer = $this->locator->get('Core\View\Renderer\PhpRenderer');
        $renderer->setResolver($resolver);

        /* @var $view \Core\View\View */
        $view = $this->locator->get('Core\View\View');
        $view->setRenderer($renderer);

        // return
        return $view;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return View
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
