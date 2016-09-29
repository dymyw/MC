<?php
/**
 * Application entrance
 *
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-11
 * @version 2016-09-29
 */

include_once 'init.inc.php';

/* @var $locator \Core\ServiceLocator\ServiceLocator */
var_dump($locator);

// \Core\ServiceLocator\ServiceLocator::has()
var_dump($locator->has('name'));

// \Core\ServiceLocator\ServiceLocator::get()
var_dump($locator->get('name'));
// \Core\ServiceLocator\ServiceLocator::__get()
var_dump($locator->info);
// callback function
var_dump($locator->db->getRow("SELECT id, product_name, product_desc FROM t_product LIMIT 1"));

// \Core\ServiceLocator\ServiceLocator::setAlias()
var_dump($locator->setAlias('info', 'i')->i);

// \Core\ServiceLocator\ServiceLocator::setService()
var_dump($locator->setService('email', 'dymayongwei@163.com')->email);
var_dump($locator->setService('name', 'mayw')->name);
try {
    $locator->setService('i', 'info - dymyw');
} catch (Exception $ex) {
    echo $ex->getMessage();
}
var_dump($locator->info);
