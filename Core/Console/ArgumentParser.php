<?php
/**
 * CLI argument parser
 *
 * @package Core_Console
 * @author Dymyw <dymayongwei@163.com>
 * @since 2016-11-25
 * @version 2016-11-25
 */

namespace Core\Console;

class ArgumentParser
{
    /**
     * Parse cli arguments
     *
     * @param array $args
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function parse(array $args)
    {
        // save all the parameters
        $params = [];

        // remove index.php
        array_shift($args);

        // controller
        if (isset($args[0]) && substr($args[0], 0, 1) !== '-') {
            $params['_controller'] = array_shift($args);
        }
        // action
        if (isset($args[0]) && substr($args[0], 0, 1) !== '-') {
            $params['_action'] = array_shift($args);
        }
        if (2 !== count($params)) {
            throw new \InvalidArgumentException("Invalid path controller action");
        }

        // save other parameters
        foreach ($args as $arg) {
            $arg = ltrim($arg, '-');
            $pair = explode('=', $arg, 2);

            // replace line-through to underline
            $name = str_replace('-', '_', $pair[0]);

            // no value
            if (1 == count($pair)) {
                $params[$name] = true;
            }
            // own value
            else {
                $value = $pair[1];
                $lowerValue = strtolower($value);

                // true
                if (in_array($lowerValue, ['1', 'true', 'yes', 'y'])) {
                    $params[$name] = true;
                }
                // false
                elseif (in_array($lowerValue, ['0', 'false', 'no', 'n'])) {
                    $params[$name] = false;
                }
                // assign value
                else {
                    $params[$name] = $value;
                }
            }
        }

        // return
        return $params;
    }
}
