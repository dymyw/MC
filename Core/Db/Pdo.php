<?php
/**
 * Pdo
 *
 * @package Core_Db
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-13
 * @version 2016-09-29
 */

namespace Core\Db;

class Pdo extends \PDO
{
    /**
     * Constructor
     *
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct($dsn, $username = null, $password = null, array $options = null)
    {
        // default driver options
        if (null === $options) {
            $options = [
                // persistent connection
                \PDO::ATTR_PERSISTENT => true,

                // mysql: return the number of found (matched) rows, not the number of changed rows
                \PDO::MYSQL_ATTR_FOUND_ROWS => true,
            ];
        }

        parent::__construct($dsn, $username, $password, $options);

        // set the default error mode
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // set the default fetch mode
        $this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    /**
     * Override the parent method
     *
     * @param string $sql
     * @return int|false
     */
    public function exec($sql)
    {
        // only pass the simple sql, don't have other parameters
        if (1 === func_num_args()) {
            // return the number of found (matched) rows, not the number of changed rows,
            // or FALSE on failure
            return parent::exec($sql);
        }
        // also pass some parameters
        else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        // return the number of found (matched) rows, not the number of changed rows
        return $this->rowCount();
    }

    /**
     * Return a single string
     *
     * @param string $sql
     * @return string|false
     * @example
     *  Pdo::getOne($sql)
     *  Pdo::getOne($sql, [$first, $second]);
     *  Pdo::getOne($sql, ['key1' => $first, 'key2' => $second])
     *  Pdo::getOne($sql, $first, $second, $third);
     */
    public function getOne($sql)
    {
        // only pass the simple sql, don't have other parameters
        if (1 === func_num_args()) {
            // \PDO::query() returns a \PDOStatement object, or FALSE on failure
            $stmt = $this->query($sql);
            if (false === $stmt) {
                return $stmt;
            }
        }
        // also pass some parameters
        else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        // fetch & return
        $value = $stmt->fetchColumn();
        $stmt->closeCursor();
        return $value;
    }

    /**
     * Return a row
     *
     * @param string $sql
     * @return string|false
     * @example
     *  Pdo::getRow($sql)
     *  Pdo::getRow($sql, [$first, $second]);
     *  Pdo::getRow($sql, ['key1' => $first, 'key2' => $second])
     *  Pdo::getRow($sql, $first, $second, $third);
     */
    public function getRow($sql)
    {
        // only pass the simple sql, don't have other parameters
        if (1 === func_num_args()) {
            // \PDO::query() returns a \PDOStatement object, or FALSE on failure
            $stmt = $this->query($sql);
            if (false === $stmt) {
                return $stmt;
            }
        }
        // also pass some parameters
        else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        // fetch & return
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return $row;
    }

    /**
     * Return all rows
     *
     * @param string $sql
     * @return string|false
     * @example
     *  Pdo::getAll($sql)
     *  Pdo::getAll($sql, [$first, $second]);
     *  Pdo::getAll($sql, ['key1' => $first, 'key2' => $second])
     *  Pdo::getAll($sql, $first, $second, $third);
     */
    public function getAll($sql)
    {
        // only pass the simple sql, don't have other parameters
        if (1 === func_num_args()) {
            // \PDO::query() returns a \PDOStatement object, or FALSE on failure
            $stmt = $this->query($sql);
            if (false === $stmt) {
                return $stmt;
            }
        }
        // also pass some parameters
        else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        // fetch & return
        return $stmt->fetchAll();
    }

    /**
     * Return the key => value pair array
     *
     * @param string $sql
     * @return string|false
     * @example
     *  Pdo::getPairs($sql)
     *  Pdo::getPairs($sql, [$first, $second]);
     *  Pdo::getPairs($sql, ['key1' => $first, 'key2' => $second])
     *  Pdo::getPairs($sql, $first, $second, $third);
     */
    public function getPairs($sql)
    {
        // only pass the simple sql, don't have other parameters
        if (1 === func_num_args()) {
            // \PDO::query() returns a \PDOStatement object, or FALSE on failure
            $stmt = $this->query($sql);
            if (false === $stmt) {
                return $stmt;
            }
        }
        // also pass some parameters
        else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        // fetch & return
        return $stmt->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
    }

    /**
     * Return the first column data
     *
     * @param string $sql
     * @return string|false
     * @example
     *  Pdo::getColumn($sql)
     *  Pdo::getColumn($sql, [$first, $second]);
     *  Pdo::getColumn($sql, ['key1' => $first, 'key2' => $second])
     *  Pdo::getColumn($sql, $first, $second, $third);
     */
    public function getColumn($sql)
    {
        // only pass the simple sql, don't have other parameters
        if (1 === func_num_args()) {
            // \PDO::query() returns a \PDOStatement object, or FALSE on failure
            $stmt = $this->query($sql);
            if (false === $stmt) {
                return $stmt;
            }
        }
        // also pass some parameters
        else {
            $stmt = call_user_func_array(array($this, 'prepareParams'), func_get_args());
            if (!$stmt) {
                return false;
            }
        }

        // fetch & return
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Prepare a SQL and execute it
     *
     * @param string $sql
     * @param mixed $params
     * @return \PDOStatement|false
     */
    protected function prepareParams($sql, $params)
    {
        // like: prepareParams($sql, $param1, $param2, $param3)
        if (is_scalar($params) || null === $params) {
            $params = func_get_args();
            array_shift($params);
        }

        // prepare & execute
        $stmt = $this->prepare($sql);
        if (!$stmt->execute((array) $params)) {
            return false;
        }

        return $stmt;
    }
}
