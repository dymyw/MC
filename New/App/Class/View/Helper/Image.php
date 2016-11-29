<?php

namespace App\View\Helper;

class Image
{
    /**
     * @var string
     */
    protected $url = null;

    /**
     * Get the url of image file
     *
     * @param string $file
     * @return Image
     */
    public function __invoke($file)
    {
        if (false !== strpos($file, '//') || ($file && '/' == $file{0})) {
            $this->url = $file;
            return $this;
        }

        $this->url = BASE_PATH . 'images' . DS . $file;

        return $this;
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
