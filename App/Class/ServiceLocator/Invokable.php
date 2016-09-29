<?php
/**
 * Invokable
 *
 * @package App_ServiceLocator
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-13
 * @version 2016-09-29
 */

namespace App\ServiceLocator;

use Core\Db\Pdo;

/**
 * Put all the invokable functions together for locator
 */
final class Invokable
{
    /**
     * Get the primary DB instance
     *
     * @return Pdo
     */
    public static function getDbInstance()
    {
        try {
            // get instance
            $db = new Pdo(
                'mysql:dbname=' . DB_DATABASE . ';host=' . DB_HOST . ';port=' . DB_PORT, DB_USERNAME, DB_PASSWORD
            );
        } catch (\PDOException $e) {
            echo 'Oops! we are truly sorry but there is been a problem executing your operation.<br />
                  Our webmaster it\'s been notified of the error.<br />
                  We apologize for the inconvenience.';
//            exit($e->getMessage());
            exit;
        }

        $db->query("SET NAMES 'UTF8'");
        $db->query("SET time_zone = '+08:00'");

        return $db;
    }
}
