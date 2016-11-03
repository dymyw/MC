<?php
/**
 * Helper interface
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-03
 */

namespace Core\View\Helper;

use Core\View\Renderer\RendererInterface;

interface HelperInterface
{
    /**
     * Set view object
     *
     * @param RendererInterface $renderer
     * @return HelperInterface
     */
    public function setView(RendererInterface $renderer);

    /**
     * @return RendererInterface
     */
    public function getView();
}
