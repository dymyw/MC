<?php

namespace App\View\Helper;

use Core\Utils\String;

class Css
{
    /**
     * @var string
     */
    protected $url = null;

    /**
     * Get the url of css file
     *
     * @param string $file
     * @param bool $checkExists
     * @return Css
     */
    public function __invoke($file, $checkExists = false)
    {
        if (false !== strpos($file, '//') || ($file && '/' == $file{0})) {
            $this->url = $file;
            return $this;
        }

        if ($checkExists && !file_exists(CSS_DIR . $file)) {
            $this->url = null;
        } else {
            $this->url = BASE_PATH . 'themes' . DS . 'default' . DS . $file;
        }

        return $this;
    }

    /**
     * Create css tag
     *
     * @param string $attrs
     * @return string
     */
    public function wrap($attrs = null)
    {
        if ($this->url) {
            $rel = substr($this->url, -9) == '.less.css' ? 'stylesheet/less' : 'stylesheet';
            return sprintf("<link rel=\"%s\" href=\"%s\"%s />\n", $rel, $this->url, String::concat($attrs));
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
