<?php
/**
 * Router
 *
 * @package Core_Router
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-17
 * @version 2016-10-17
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
     * Get the parent parse path
     *
     * @param string $path
     * @return string|bool
     */
    public function getParentPath($path)
    {
        if (empty($path)) {
            return '*';
        }
        elseif ('*' === $path) {
            return false;
        }
        elseif ('*' === $path{0}) {
            return '*';
        }

        $segments = explode('/', $path);
        foreach ($segments as $i => $segment) {
            if ('*' === $segment) {
                $i--;
                break;
            }
        }
        $segments[$i] = '*';
        return implode('/', $segments);
    }

    /**
     * Create URL
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function createUrl($path, array $params = [])
    {
        // get the creation rules
        $parsedRules = $this->getRuleParser()->getParsedRules();
        $pairsIdentifier = $this->getRuleParser()->getPairsIdentifier();
        $uriDelimiter = $this->getRuleParser()->getUriDelimiter();
        $pathParams = $this->parsePath($path);

        // filter $params
        foreach ($params as $param => $value) {
            if (null === $value || [] === $value) {
                unset($params[$param]);
            }
        }

        // search path
        do {
            if (!isset($parsedRules[$path])) {
                continue;
            }

            // exists the path
            $rules = $parsedRules[$path];

            foreach ($rules as $options) {
                // it doesn't exist some required parameter (except pairs and path identifiers)
                foreach ($options['requiredParams'] as $name) {
                    if (
                        !array_key_exists($name, $params)
                        && $name != $pairsIdentifier
                        && !in_array($name, $this->getPathIdentifiers())

                    ) { continue 2; }
                }

                // pair array & query array
                $pairArray = [];
                $queryArray = [];
                $keyRegExp = isset($options['paramsRegExp'][$pairsIdentifier]['regExpKey']) ? $options['paramsRegExp'][$pairsIdentifier]['regExpKey'] : null;
                foreach ($params as $name => $value) {
                    if (!in_array($name, $options['allParams'])) {
                        if ($keyRegExp && preg_match($keyRegExp, $name)) {
                            $pairArray[$name] = $value;
                        } else {
                            $queryArray[$name] = $value;
                        }
                    }
                }

                if ($keyRegExp) {
                    // add $pairArray to $params
                    if ($pairArray) {
                        $params[$pairsIdentifier] = $pairArray;
                    }
                    // the pairs identifier is required, but it does not exist
                    else {
                        continue;
                    }
                }

                // get query string
                $queryString = http_build_query($queryArray);

                // found
                $tr = [];
                $params = array_merge($params, $pathParams);
                foreach ($options['allParams'] as $name) {
                    $search = '<' . $name . '>';
                    $replace = '';

                    if (isset($params[$name])) {
                        // array params
                        if (is_array($params[$name])) {
                            $arrayParams = [];

                            if (!$options['paramsRegExp'][$name]['isArray']) {
                                throw new \RuntimeException("Index {$name} should not be the array parameter.");
                            }

                            foreach ($params[$name] as $k => $v) {
                                if (is_array($v)) {
                                    $str = var_export($v, true);
                                    throw new \RuntimeException("Invalid creating URL parameter name: {$k}, parameters are {$str}");
                                }

                                $regExp = $options['paramsRegExp'][$name]['regExp'];

                                // is query identifier
                                if ($name == $pairsIdentifier) {
                                    $v = $k . $uriDelimiter . $v;
                                    $regExp = $options['paramsRegExp'][$name]['regExpPairs'];
                                }

                                preg_match_all($regExp, $v, $matches);
                                if ($matches[0]) {
                                    $arrayParams[] = rawurlencode($v);
                                } else {
                                    throw new \RuntimeException("Index {$name}: {$v} not match regExp: {$regExp}");
                                }
                            }

                            $replace = implode($uriDelimiter, $arrayParams);
                        }
                        // string parameters
                        else {
                            preg_match_all($options['paramsRegExp'][$name]['regExp'], $params[$name], $matches);
                            if ($matches[0]) {
                                $replace = rawurlencode((string) $params[$name]);
                            } else {
                                throw new \RuntimeException("Index {$name}: {$params[$name]} not match regExp: " . $options['paramsRegExp'][$name]['regExp']);
                            }
                        }

                        $replace = $options['paramsRegExp'][$name]['before'] . $replace . $options['paramsRegExp'][$name]['after'];
                    }

                    $tr[$search] = $replace;
                }

                // create url
                $url = strtr($options['template'], $tr);

                // return
                switch ($this->getRouteMode()) {
                    case self::ROUTE_MODE_URLREWRITE:
                        return $this->basePath . $url . ($queryString ? '?' . $queryString : '');
                        break;

                    case self::ROUTE_MODE_PATHINFO:
                        return $this->basePath . 'index.php/' . $url . ($queryString ? '?' . $queryString : '');
                        break;

                    default:
                        throw new \RuntimeException("Invalid route mode: " . $this->getRouteMode());
                }
            }
        } while ($path = $this->getParentPath($path));

        // throw exception
        throw new \InvalidArgumentException("Invalid path: {$path}");
    }
}
