<?php

namespace App\View\Helper;

use Core\Utils\String;

class Js
{
    /**
     * @var string
     */
    protected $url = null;

    /**
     * Get the url of javascript file
     *
     * @param string $file
     * @param bool $checkExists
     * @return Js
     */
    public function __invoke($file, $checkExists = false)
    {
        if (false !== strpos($file, '//') || ($file && '/' == $file{0})) {
            $this->url = $file;
            return $this;
        }

        if ($checkExists && !file_exists(JS_DIR . $file)) {
            $this->url = null;
        } else {
            $this->url = BASE_PATH . 'js' . DS . $file;
        }

        return $this;
    }

    /**
     * Create script tag
     *
     * @param string $attrs
     * @return string
     */
    public function wrap($attrs = null)
    {
        if ($this->url) {
            return sprintf("<script src=\"%s\"%s></script>\n", $this->url, String::concat($attrs));
        }

        return '';
    }

    /**
     * Magic method
     *
     * @return string
     */
	public function __toString()
	{
		return $this->url;
	}
}
