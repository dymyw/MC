<?php
/**
 * View resolver interface
 *
 * @package Core_View_Resolver
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-11-01
 */

namespace Core\View\Resolver;

interface ResolverInterface
{
    /**
     * Resolve a template/pattern name to a resource the renderer can consume
     *
     * @param string $name
     * @return mixed
     * @throws \RuntimeException
     */
    public function resolve($name);
}
