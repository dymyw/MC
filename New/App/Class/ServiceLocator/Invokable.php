<?php

namespace App\ServiceLocator;

use Core\Db\Pdo;
use Core\ServiceLocator\ServiceLocator;
use Core\Router\RuleParser;
use Core\Router\Router;
use Core\Console\ArgumentParser;

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

    /**
     * Get all the query parameters
     *
     * @param ServiceLocator $locator
     * @return array
     */
    public static function getParams(ServiceLocator $locator)
    {
        /* @var $ruleParser RuleParser */
        $ruleParser = new RuleParser;
        $ruleParser->setRules(include CONFIG_DIR . 'Router.php')->getParsedRules();

        /* @var $router Router */
        $router = new Router($ruleParser);
        $router->setBasePath(BASE_PATH);
//        $router->setRouteMode(Router::ROUTE_MODE_PATHINFO);

        // get cli parameters
        if ('cli' == PHP_SAPI) {
            $params = ArgumentParser::parse($_SERVER['argv']);
        }
        // get parameters
        else {
            $params = $router->parseUrl();
        }

        // add Router service
        $locator->setService('router', $router);

        // return
        return $params;
    }

    /**
     * Get the redis instance
     *
     * @return \Redis
     */
    public function getRedisInstance()
    {
        if (REDIS_ENABLED) {
            try {
                // get instance
                $redis = new \Redis();
                $redis->pconnect(REDIS_HOST, REDIS_PORT);
                REDIS_AUTH && $redis->auth(REDIS_AUTH);
                $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
                return $redis;
            } catch (\RedisException $e) {}
        }

        return null;
    }
}
