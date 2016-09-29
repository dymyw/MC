<?php
/**
 * AutoLoader class
 *
 * @package Core_Loader
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-12
 * @version 2016-09-29
 */

namespace Core\Loader;

class AutoLoader
{
    /**
     * @var array
     */
    protected static $namespaces = [];

    /**
     * Set namespaces
     *
     * @param array $namespaces
     * @example
     *  AutoLoader::setNamespaces([
     *      'Core' => CORE_DIR,
     *      'App' => APP_DIR . 'Class' . DS,
     *  ]);
     */
    public static function setNamespaces(array $namespaces)
    {
        self::$namespaces = $namespaces;
    }

    /**
     * Set a namespace
     *
     * @param string $name
     * @param string $dir
     */
    public static function setNamespace($name, $dir)
    {
        self::$namespaces[$name] = $dir;
    }

    /**
     * Load a class
     *
     * @param string $className
     * @param string $suffix
     * @return bool
     */
    public static function load($className, $suffix = '.php')
    {
        $ns = self::$namespaces;

        $trunk = strtr($className, ['_' => DS, '\\' => DS]);
        $prefix = strstr($trunk, DS, true);
        $path = substr(strstr($trunk, DS), 1);

        // find class by the namespaces
        if (isset($ns[$prefix])) {
            $file = $ns[$prefix] . $path . $suffix;
            if (file_exists($file)) {
                include_once $file;
                return true;
            }
        }

        // find class from every namespace
        $ns = array_reverse($ns);
        foreach ($ns as $dir) {
            $file = $dir . $trunk . $suffix;
            if (file_exists($file)) {
                include_once $file;
                return true;
            }
        }

        return false;
    }

    /**
     * Find a class from the namespaces
     *
     * @param string $baseName
     * @param string $suffix
     * @return string|bool
     */
    public static function find($baseName, $suffix = '.php')
    {
        $path = strtr($baseName, ['_' => DS, '\\' => DS]);

        $ns = array_reverse(self::$namespaces);
        foreach ($ns as $name => $dir) {
            if (file_exists($dir . $path . $suffix)) {
                return strtr("{$name}\\{$path}", [DS => '\\']);
            }
        }

        return false;
    }

    /**
     * Register autoload
     */
    public static function register()
    {
        spl_autoload_register(['static', 'load']);
    }
}
