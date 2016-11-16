<?php
/**
 * AbstractHelper
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-16
 */

namespace Core\View\Helper;

use Core\View\Renderer\RendererInterface;

abstract class AbstractHelper implements HelperInterface
{
    /**
     * @var RendererInterface
     */
    protected $view = null;

    /**
     * Set view object
     *
     * @param RendererInterface $renderer
     * @return HelperInterface
     */
    public function setView(RendererInterface $renderer)
    {
        $this->view = $renderer;
        return $this;
    }

    /**
     * @return RendererInterface
     */
    public function getView()
    {
        return $this->view;
    }
}
