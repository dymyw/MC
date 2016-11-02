<?php
/**
 * Application entrance
 *
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-11
 * @version 2016-11-02
 */

include_once 'init.inc.php';

/* @var $locator \Core\ServiceLocator\ServiceLocator */
$params = $locator->get('params');
$controller = &$params['_controller'];
$action = &$params['_action'];

/* @var $front \Core\Controller\FrontController */
$front = $locator->frontController;
$result = $front->dispatch($controller, $action);
$front->run($result);
