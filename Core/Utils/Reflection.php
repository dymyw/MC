<?php
/**
 * Reflection utils
 *
 * @package Core_Utils
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-14
 * @version 2016-09-30
 */

namespace Core\Utils;

class Reflection
{
    /**
     * Automatically create new instance by class name and parameters indexed by parameter name
     *
     * @param string $className
     * @param array $params
     * @return object
     */
    public static function newInstance($className, array $params = [])
    {
        $class = new \ReflectionClass($className);
        $invokableParams = [];
        if ($class->hasMethod('__construct')) {
            $method = $class->getMethod('__construct');
            $invokableParams = self::getInvokableParams($method->getParameters(), $params);
        }

        return $class->newInstanceArgs($invokableParams);
    }

    /**
     * Invoke some method of some object
     *
     * @param object $object
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws \ReflectionException
     */
    public static function invokeMethod($object, $method, array $params = [])
    {
        $class = new \ReflectionClass($object);
        if ($class->hasMethod($method)) {
            $method = $class->getMethod($method);
            $invokableParams = self::getInvokableParams($method->getParameters(), $params);

            return $method->invokeArgs(is_string($object) ? null : $object, $invokableParams);
        }

        throw new \ReflectionException("Attempt to invoke a nonexistent method: {$method}");
    }

    /**
     * Invoke some closure
     *
     * @param string $function
     * @param array $params
     * @return mixed
     */
    public static function invokeFunction($function, array $params = [])
    {
        $func = new \ReflectionFunction($function);
        $invokableParams = self::getInvokableParams($func->getParameters(), $params);

        return $func->invokeArgs($invokableParams);
    }

    /**
     * Get the invokable parameters indexed by parameter position
     *
     * @param array $paramObjects
     * @param array $params
     * @return array
     * @throws \ReflectionException
     */
    protected static function getInvokableParams(array $paramObjects, array $params)
    {
        $invokableParams = [];
        foreach ($paramObjects as $parameter) {
            /* @var $parameter \ReflectionParameter */
            $name = $parameter->getName();
            if (array_key_exists($name, $params)) {
                $invokableParams[] = $params[$name];
            } elseif ($parameter->isOptional()) {
                $invokableParams[] = $parameter->getDefaultValue();
            } else {
                throw new \ReflectionException("Missing parameter: {$name}");
            }
        }

        return $invokableParams;
    }
}
