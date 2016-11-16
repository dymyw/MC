<?php
/**
 * JsonModel
 *
 * @package Core_View_Model
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-15
 * @version 2016-11-16
 */

namespace Core\View\Model;

use Core\View\Variables as ViewVariables;

class JsonModel extends ViewModel
{
    /**
     * JSONP callback (if set, wraps the return in a function call)
     *
     * @var string
     */
    protected $jsonpCallback = null;

    /**
     * Constructor
     *
     * @param string|array|\ArrayAccess|\Traversable $variablesOrStatus
     * @param string $msg
     * @param mixed $data
     */
    public function __construct($variablesOrStatus = '', $msg = '', $data = [])
    {
        // initializing the variables container
        $this->variables = new ViewVariables;

        // set status
        if (is_string($variablesOrStatus)) {
            $this->setVariable('status', $variablesOrStatus);
        }
        // set variables
        elseif ($variablesOrStatus) {
            $this->setVariables($variablesOrStatus, true);
        }

        // set msg
        if ($msg) {
            $this->setVariable('msg', (string) $msg);
        }

        // set data
        if ($data) {
            $this->setVariable('data', $data);
        }
    }

    /**
     * Initializing JsonModel
     *
     * @param string|array|\ArrayAccess|\Traversable $variablesOrStatus
     * @param string $msg
     * @param mixed $data
     * @return JsonModel
     */
    public static function init($variablesOrStatus = 'succ', $msg = '', $data = [])
    {
        return new self($variablesOrStatus, $msg, $data);
    }

    /**
     * Set status, msg and data
     *
     * @param string $status enum('succ', 'error', '...')
     * @param string $msg
     * @param mixed $data
     * @return JsonModel
     */
    public function setStatus($status, $msg = '', $data = [])
    {
        $this->setVariable('status', $status);
        if ($msg) {
            $this->setVariable('msg', (string) $msg);
        }
        if ($data) {
            $this->setVariable('data', $data);
        }

        return $this;
    }

    /**
     * Set redirect and the delay seconds
     *
     * @param string $redirect
     * @param int $delay
     * @return JsonModel
     */
    public function setRedirect($redirect, $delay = 0)
    {
        $this->setVariable('redirect', (string) $redirect);
        if ($delay) {
            $this->setVariable('delay', (int) $delay);
        }

        return $this;
    }

    /**
     * Set JavaScript what will be executed
     *
     * @param string $script
     * @return JsonModel
     */
    public function setScript($script)
    {
        $this->setVariable('script', $script);
        return $this;
    }

    /**
     * Set callback function, context object and the parameters
     *
     * @param string $callback
     * @param mixed $data
     * @param string $context
     * @return JsonModel
     */
    public function setCallback($callback, $data = [], $context = '')
    {
        $this->setVariable('callback', $callback);
        if ($data) {
            $this->setVariable('data', $data);
        }
        if ($context) {
            $this->setVariable('context', $context);
        }

        return $this;
    }

    /**
     * Set the JSONP callback function name
     *
     * @param string $callback
     * @return JsonModel
     */
    public function setJsonpCallback($callback)
    {
        $this->jsonpCallback = $callback;
        return $this;
    }

    /**
     * Serialize to JSON
     *
     * @return string
     */
    public function serialize()
    {
        $variables = (array) $this->getVariables();

        if ($this->jsonpCallback) {
            return $this->jsonpCallback . '(' . json_encode($variables) . ');';
        }

        return json_encode($variables);
    }
}
