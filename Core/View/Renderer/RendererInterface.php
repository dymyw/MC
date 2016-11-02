<?php
/**
 * View renderer interface
 *
 * @package Core_View_Renderer
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-11-01
 */

namespace Core\View\Renderer;

use Core\View\Resolver\ResolverInterface;
use Core\View\Model\ViewModelInterface;

interface RendererInterface
{
    /**
     * Return the template engine object, if any
     *
     * If using a third-party template engine, such as Smarty, patTemplate,
     * phplib, etc, return the template engine object. Useful for calling
     * methods on these objects, such as for setting filters, modifiers, etc.
     *
     * @return mixed
     */
    public function getEngine();

    /**
     * Set the resolver used to map a template name to a resource the renderer may consume
     *
     * @param ResolverInterface $resolver
     * @return RendererInterface
     */
    public function setResolver(ResolverInterface $resolver);

    /**
     * Processes a view script and returns the output
     *
     * @param string|ViewModelInterface $nameOrModel The script/resource process, or a view model
     * @param null|array|\ArrayAccess $values Values to use during rendering
     * @return string
     * @throws \RuntimeException
     */
    public function render($nameOrModel, $values = null);
}
