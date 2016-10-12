<?php
/**
 * Application entrance
 *
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-11
 * @version 2016-10-12
 */

include_once 'init.inc.php';

/* @var $locator \Core\ServiceLocator\ServiceLocator */
$params = $locator->get('params');
$controller = &$params['_controller'];
$action = &$params['_action'];
var_dump($params, $controller, $action);

/**
 *
array (size=2)
  '_controller' => &string 'default' (length=7)
  '_action' => &string 'index' (length=5)

array (size=3)
  'eyeglasses/gender' => string '' (length=0)
  '_controller' => &string 'eyeglasses' (length=10)
  '_action' => &string 'gender' (length=6)

array (size=5)
  'women-eyeglasses-page-12_html' => string '' (length=0)
  'gender' => string 'women' (length=5)
  'page' => string '12' (length=2)
  '_controller' => &string 'eyeglasses' (length=10)
  '_action' => &string 'gender' (length=6)

array (size=6)
  'fashion-women-eyeglasses-width-140_html' => string '' (length=0)
  'attrs' =>
    array (size=1)
      0 => string 'fashion' (length=7)
  'gender' => string 'women' (length=5)
  'width' => string '140' (length=3)
  '_controller' => string 'eyeglasses' (length=10)
  '_action' => string 'list' (length=4)

array (size=9)
  'fashion-red-women-top10-bestseller-eyeglasses-width-140-height-22-12_html' => string '' (length=0)
  'attrs' =>
    array (size=2)
      0 => string 'fashion' (length=7)
      1 => string 'red' (length=3)
  'gender' => string 'women' (length=5)
  'tags' =>
    array (size=2)
      0 => string 'top10' (length=5)
      1 => string 'bestseller' (length=10)
  'width' => string '140' (length=3)
  'height' => string '22' (length=2)
  'page' => string '12' (length=2)
  '_controller' => string 'eyeglasses' (length=10)
  '_action' => string 'list' (length=4)
 */
