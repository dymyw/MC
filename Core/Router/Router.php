<?php
/**
 * Router
 *
 * @package Core_Router
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-17
 * @version 2016-10-12
 */

namespace Core\Router;

use Core\Router\RuleParser;

class Router implements RouterInterface
{
    /**
     * The base path of URLs, include '/'
     *
     * @var string
     */
    protected $basePath = '/';

    /**
     * Route Modes
     */
    const ROUTE_MODE_PATHINFO = 'pathinfo';
    const ROUTE_MODE_URLREWRITE = 'urlrewrite';

    /**
     * Default route mode
     *
     * @var string
     */
    protected $routeMode = self::ROUTE_MODE_URLREWRITE;

    /**
     * @var RuleParser
     */
    protected $ruleParser = null;

    /**
     * Parse to _GET
     *
     * @var bool
     */
    protected $parseToGet = true;

    /**
     * Path identifiers
     *
     * @var array
     */
    protected $pathIdentifiers = ['_controller', '_action'];

    /**
     * Constructor
     *
     * @param RuleParser $ruleParser
     * @param bool $parseToGet
     */
    public function __construct(RuleParser $ruleParser, $parseToGet = true)
    {
        $this->setRuleParser($ruleParser);
        $this->parseToGet = $parseToGet;
    }

    /**
     * Set the rule parser
     *
     * @param RuleParser $ruleParser
     * @return Router
     */
    public function setRuleParser(RuleParser $ruleParser)
    {
        $this->ruleParser = $ruleParser;
        return $this;
    }

    /**
     * Get the rule parser
     *
     * @return RuleParser
     */
    public function getRuleParser()
    {
        return $this->ruleParser;
    }

    /**
     * Set the base path of URLs
     *
     * @param string $basePath
     * @return Router
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * Get the base path of URLs
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set the route mode
     *
     * @param string $routeMode
     * @return Router
     */
    public function setRouteMode($routeMode)
    {
        $this->routeMode = $routeMode;
        return $this;
    }

    /**
     * Get the route mode
     *
     * @return string
     */
    public function getRouteMode()
    {
        return $this->routeMode;
    }

    /**
     * Set the path identifiers
     *
     * @param array $identifiers
     * @return Router
     */
    public function setPathIdentifiers(array $identifiers)
    {
        $this->pathIdentifiers = $identifiers;
        return $this;
    }

    /**
     * Get the path identifiers
     *
     * @return array
     */
    public function getPathIdentifiers()
    {
        return $this->pathIdentifiers;
    }

    /**
     * Get the request URL, which doesn't include the leading '/' and the query string
     * It has been url-decoded
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getRequestUrl()
    {
        $requestUrl = '';
        $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        switch ($this->routeMode) {
            case self::ROUTE_MODE_PATHINFO :
                $requestUrlTemp = substr($urlPath, strlen($_SERVER['SCRIPT_NAME']));
                break;

            case self::ROUTE_MODE_URLREWRITE :
                // remove the base path
                $requestUrlTemp = substr($urlPath, strlen($this->basePath));
                break;

            default :
                throw new \RuntimeException("Unknown route mode: {$this->routeMode}");
        }

        // raw urldecode
        $requestUrl = rawurldecode($requestUrlTemp);

        // return
        return $requestUrl;
    }

    /**
     * Parse URL
     *
     * @param string $requestUrl
     * @return array
     */
    public function parseUrl($requestUrl = null)
    {
        // request URL
        if (null === $requestUrl) {
            $requestUrl = ltrim($this->getRequestUrl(), '/');
        }

        // get the parsed rules
        $rules = $this->getRuleParser()->getParsedRules();

        $params = $_GET;
        // pairs identifier
        $pairsIdentifier = $this->getRuleParser()->getPairsIdentifier();
        // uri delimiter
        $uriDelimiter = $this->getRuleParser()->getUriDelimiter();
        foreach ($rules as $path => $pathRules) {
            foreach ($pathRules as $regExp => $options) {
                // find the right rule
                if (preg_match($regExp, $requestUrl, $matches)) {
                    foreach ($matches as $key => $val) {
                        if (!is_numeric($key)) {
                            if ($options['paramsRegExp'][$key]['isArray']) {
                                if ($key == $pairsIdentifier) {
                                    preg_match_all($options['paramsRegExp'][$key]['regExpPairs'], $val, $pairs);
                                    foreach ($pairs[0] as $pairStr) {
                                        $pair = explode($uriDelimiter, $pairStr);
                                        $params[$pair[0]] = $pair[1];
                                    }
                                } else {
                                    !empty($val) && $params[$key] = explode($uriDelimiter, $val);
                                }
                            } else {
                                $params[$key] = $val;
                            }
                        }
                    }

                    // merge together
                    $params = array_merge($params, $this->parsePath($path));

                    // break
                    break 2;
                }
            }
        }

        // parse to _GET
        if ($this->parseToGet) {
            $_GET = $params;
        }

        return $params;
    }

    /**
     * Parse the path to an array
     *
     * @param string $path
     * @return array
     */
    public function parsePath($path)
    {
        $segments = explode('/', $path);

        $params = [];
        foreach ($segments as $i => $segment) {
            if ('*' !== $segment && isset($this->pathIdentifiers[$i])) {
                $params[$this->pathIdentifiers[$i]] = $segment;
            }
        }
        return $params;
    }

    /**
     * Create URL
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function createUrl($path, array $params)
    {

    }
}
