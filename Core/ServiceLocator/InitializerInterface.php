<?php
/**
 * Initializer interface
 *
 * @package Core_ServiceLocator
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-12
 * @version 2016-10-19
 */

namespace Core\ServiceLocator;

/**
 * It will automatically run initialize() before get a cached plugin via plugin manager
 */
interface InitializerInterface
{
    public function initialize();
}
