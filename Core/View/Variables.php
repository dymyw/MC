<?php
/**
 * Variables
 *
 * @package Core_View
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-15
 * @version 2016-10-21
 */

namespace Core\View;

class Variables extends \ArrayObject
{
    /**
     * Assign many values at once
     *
     * @param array|object $spec
     * @return Variables
     * @throws \InvalidArgumentException
     */
    public function assign($spec)
    {
        if (is_object($spec)) {
            if (method_exists($spec, 'toArray')) {
                $spec = $spec->toArray();
            } else {
                $spec = (array) $spec;
            }
        }

        if (!is_array($spec)) {
            throw new \InvalidArgumentException(sprintf(
                'assign() expects either an array or an object as an argument; received "%s"',
                gettype($spec)
            ));
        }

        foreach ($spec as $key => $value) {
            $this[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the variable value
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        if (!$this->offsetExists($key)) {
            return null;
        }

        $return = parent::offsetGet($key);

        // if we have a closure/functor, invoke it, and return its return value
        if (is_object($return) && is_callable($return)) {
            $return = call_user_func($return);
        }

        return $return;
    }

    /**
     * Clear all variables
     *
     * @return void
     */
    public function clear()
    {
        $this->exchangeArray([]);
    }
}
