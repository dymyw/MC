<?php
/**
 * Url helper
 *
 * @package Core_View_Helper
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-20
 * @version 2016-11-24
 */

namespace Core\View\Helper;

use Core\ServiceLocator\ServiceLocatorAwareInterface;
use Core\ServiceLocator\ServiceLocator;
use Core\ServiceLocator\InitializerInterface;
use Core\Utils\Http;

class Url implements ServiceLocatorAwareInterface, InitializerInterface
{
    /**
     * @var ServiceLocator|\App\Hint\ServiceLocator
     */
    protected $locator = null;

    /**
     * @var string
     */
    private $path = null;

    /**
     * @var array
     */
    private $params = null;

    /**
     * @var bool
     */
    private $https = false;

    /**
     * @var bool
     */
    private $forceHost = false;

    /**
     * Initialize the URL
     *
     * @param string $path
     * @param array $params
     * @param bool $https
     * @param bool $forceHost
     * @return Url
     */
    public function __invoke($path = 'default/index', $params = [], $https = false, $forceHost = false)
    {
        $this->path = $path;
        $this->params = (array) $params;
        $this->https = $https;
        $this->forceHost = $forceHost;
        return $this;
    }

    /**
     * Set action path
     *
     * @param string $path
     * @return Url
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get action path
     *
     * @return string
     */
    public function getPath()
    {
        if (null === $this->path) {
            // controller mode
            if ($this->locator->has('controller')) {
                $params = $this->locator->get('params');
                $this->setPath($params['_controller'] . '/' . $params['_action']);
            }
            // not controller mode
            else {
                $this->setPath(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            }
        }

        return $this->path;
    }

    /**
     * Add parameters (only merge to the origin parameters)
     *
     * @param array $params
     * @return Url
     */
    public function addParams(array $params)
    {
        $this->getParams();

        foreach ($params as $key => $value) {
            if (
                isset($this->params[$key])
                && (is_array($this->params[$key]) || is_array($value))
            ) {
                $this->params[$key] = array_unique(array_merge((array) $this->params[$key], (array) $value));
            } else {
                $this->params[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Remove some parameters
     *
     * @param array|string $array
     * @return Url
     */
    public function removeParams($array)
    {
        $this->getParams();

        // diff
        foreach ((array) $array as $key => $item) {
            if (is_numeric($key)) {
                unset($this->params[$item]);
            } elseif (array_key_exists($key, $this->params)) {
                $this->params[$key] = array_diff((array) $this->params[$key], (array) $item);
            }
        }

        return $this;
    }

    /**
     * Create URL
     *
     * @return string
     */
    public function __toString()
    {
        // path
        $path = $this->getPath();
        // params
        $params = $this->getParams();

        // create url
        $url = $this->locator->router->createUrl($path, $params);

        if (Http::isHttps() === $this->https && false === $this->forceHost) {
            return $url;
        }

        $host = ($this->https ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        return $host . $url;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return Url
     */
    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }

    /**
     * Reset the object
     *
     * @return Url
     */
    public function initialize()
    {
        $this->path = null;
        $this->params = null;
        $this->https = false;
        $this->forceHost = false;
        return $this;
    }

    /**
     * Get all the parameters
     *
     * @return array
     */
    protected function getParams()
    {
        if (null === $this->params) {
            $this->params = $this->locator->get('params');
            unset($this->params['_controller'], $this->params['_action']);
        }

        return $this->params;
    }
}
