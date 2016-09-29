<?php
/**
 * Application entrance
 *
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-11
 * @version 2016-09-29
 */

include_once 'init.inc.php';

use Core\Db\Pdo;
try {
    // chinese: charset=utf8
    $db = new Pdo('mysql:host=localhost;dbname=test;port=3306;charset=utf8', 'root', 'dymyw');

    // chinese
//    $db->exec("SET NAMES 'utf8'");

    // getOne
    echo $db->getOne("SELECT COUNT(*) FROM t_product WHERE online = 1");
    echo $db->getOne("SELECT COUNT(*) FROM t_product WHERE online = ?", 1);
    echo $db->getOne("SELECT COUNT(*) FROM t_product WHERE online = ?", [1]);
    echo $db->getOne("SELECT COUNT(*) FROM t_product WHERE online = :online", ['online' => 1]);

    // getRow
    $row = $db->getRow("SELECT id, product_name, product_desc FROM t_product WHERE online = :online AND id = :id", ['online' => 1, 'id' => 63]);
    var_dump($row);

    // getAll
    $all = $db->getAll("SELECT id, product_name, product_desc FROM t_product WHERE online = :online LIMIT 5", ['online' => 1]);
    var_dump($all);

    // getPairs
    $pairs = $db->getPairs("SELECT id, product_name, product_desc FROM t_product WHERE online = :online LIMIT 5", ['online' => 1]);
    var_dump($pairs);

    // getColumn
    $column = $db->getColumn("SELECT id, product_name, product_desc FROM t_product WHERE online = :online LIMIT 5", ['online' => 1]);
    var_dump($column);
} catch (\PDOException $ex) {
    exit($ex->getMessage());
}
