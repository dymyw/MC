<?php
/**
 * RuleParser
 *
 * @package Core_Router
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-16
 * @version 2016-10-17
 */

namespace Core\Router;

class RuleParser implements RuleParserInterface
{
    /**
     * The delimiter between the parameters
     *
     * @var char
     */
    protected $uriDelimiter = '-';

    /**
     * Pairs identifier
     *
     * @var string
     */
    protected $pairsIdentifier = '_pairs';

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * Whether or not be parsed
     *
     * @var bool
     */
    protected $parsed = false;

    /**
     * @var array
     */
    protected $parsedRules = [];

    /**
     * Set URI delimiter
     *
     * @param char $delimiter
     * @return RuleParser
     * @throws \InvalidArgumentException
     */
    public function setUriDelimiter($delimiter)
    {
        if (1 != strlen($delimiter)) {
            throw new \InvalidArgumentException('Error URI delimiter, only accept character here.');
        }

        $this->uriDelimiter = $delimiter;
        return $this;
    }

    /**
     * Get URI delimiter
     *
     * @return char
     */
    public function getUriDelimiter()
    {
        return $this->uriDelimiter;
    }

    /**
     * Set the pairs identifier
     *
     * @param string $identifier
     * @return RuleParser
     */
    public function setPairsIdentifier($identifier)
    {
        $this->pairsIdentifier = $identifier;
        return $this;
    }

    /**
     * Get the pairs identifier
     *
     * @return string
     */
    public function getPairsIdentifier()
    {
        return $this->pairsIdentifier;
    }

    /**
     * Set the router rules
     *
     * @param array $rules
     * @return RuleParser
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Append rules
     *
     * @param array $rules
     * @return RuleParser
     */
    public function appendRules(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);
        return $this;
    }

    /**
     * Prepend rules
     * The new rules will be executed first
     *
     * @param array $rules
     * @return RuleParser
     */
    public function prependRules(array $rules)
    {
        $this->rules = array_merge($rules, $this->rules);
        return $this;
    }

    /**
     * Get the router rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Get the parsed rules
     *
     * @return array
     */
    public function getParsedRules()
    {
        if (!$this->parsed) {
            $this->parseRules();
        }

        return $this->parsedRules;
    }

    /**
     * To parse all the rules
     *
     * @return array
     */
    protected function parseRules()
    {
        // set 'parsed'
        $this->parsed = true;

        // parsed rules
        $parsedRules = [];

        // start to parse
        foreach ($this->rules as $path => $rules) {
            if (!isset($parsedRules[$path])) {
                $parsedRules[$path] = [];
            }

            foreach ((array) $rules as $rule) {
                $rule = $this->replacePairsWildcard($rule);
                $template = $this->getTemplate($rule);

                $rule = $this->quoteRule($rule);
                $array = $this->parseStandardRule($rule);

                $pattern = $array['pattern'];
                $parsedRules[$path][$pattern] = [
                    'template' => $template,
                    'allParams' => $array['allParams'],
                    'requiredParams' => $array['requiredParams'],
                    'paramsRegExp' => $array['paramsRegExp'],
                ];
            }
        }

        $this->parsedRules = $parsedRules;
        return $this->parsedRules;
    }

    /**
     * Replace the pairs wildcard(*) to pairs identifier(_pairs)
     *
     * @param string $rule
     * @return string
     */
    protected function replacePairsWildcard($rule)
    {
        return str_replace('<*', '<' . $this->pairsIdentifier, $rule);
    }

    /**
     * Get the template string of creating URLs by the human rule
     *
     * @param string $rule
     * @return string
     */
    protected function getTemplate($rule)
    {
        return preg_replace('/<(?:\W*)(\w+)[^\>]*>/', '<\1>', $rule);
    }

    /**
     * Quote all the normal parts what are outside of the < and >
     *
     * product-list/<M>/<gender:\w*>-<attrs:\w+>-eyeglasses.html
     *  => product\-list/<M>/<gender:\w*>\-<attrs:\w+>\-eyeglasses\.html
     *
     * @param string $rule
     * @return string
     */
    protected function quoteRule($rule)
    {
        $regExp = '# [^\>]+(?=\<) | (?<=\>)[^\<]+ #x';
        $quotedRule = preg_replace_callback($regExp, function($matches) {
            return preg_quote($matches[0], '#');
        }, $rule);

        return $quotedRule;
    }

    /**
     * Get rule params
     *
     * @param string $rule
     * @return array
     * @throws \RuntimeException
     */
    protected function parseStandardRule($rule)
    {
        $allParams = [];
        $requiredParams = [];
        $paramsRegExp = [];

        /**
         * The return array of RegExp will be:
         *  [1] delimiter
         *  [2] parameter name
         *  [3] required sign & array sign
         *  [4] the regExp of parameter
         *  [5] delimiter
         */
        $regExpParam = '# \< (~)? (\w+) ([^\:\>]?) (?: \:([^\>\~]+)) (~)? \> #x';

        // callback
        $callback = function($matches) use (&$allParams, &$requiredParams, &$paramsRegExp) {
            $before = $matches[1] ? $this->uriDelimiter : '';

            // parameter name
            $param = $matches[2];

            // pairs identifier
            if ($param == $this->pairsIdentifier) {
                $matches[3] = '+';
            }

            $after = empty($matches[5]) ? '' : $this->uriDelimiter;

            /**
             * Whether or not be required or array paramter:
             *  (?)         not array parameter     not required
             *  (notset)    not array parameter     required
             *  (*)         array parameter         not required
             *  (+)         array parameter         required
             */
            $sign = $matches[3];
            if ('?' === $sign) {
                $isArrayParam = false;
                $isRequiredParam = false;
            } elseif ('' === $sign) {
                $isArrayParam = false;
                $isRequiredParam = true;
            } elseif ('*' === $sign) {
                $isArrayParam = true;
                $isRequiredParam = false;
            } elseif ('+' === $sign) {
                $isArrayParam = true;
                $isRequiredParam = true;
            } else {
                throw new \RuntimeException("Invalid required or array sign: {$sign}");
            }

            // all parameters
            $allParams[] = $param;

            // require parameters
            if ($isRequiredParam) {
                $requiredParams[] = $param;
            }

            $regExp = '';
            // be not array parameter
            if (!$isArrayParam) {
                $regExp = $matches[4];
            }
            // get the regExp of parameter
            else {
                if ($param == $this->pairsIdentifier) {
                    $regExpTemp = '(?:' . $matches[4] . ')[\\' . $this->uriDelimiter . '](?:[^\\' . $this->uriDelimiter . ']+)';
                    $paramsRegExp[$param]['regExpKey'] = '#^' . $matches[4] . '$#i';
                    $paramsRegExp[$param]['regExpPairs'] = '#' . $regExpTemp . '#i';
                } else {
                    $regExpTemp = $matches[4];
                }

                $regExp = '(?:' . $regExpTemp . ')(?:[\\' . $this->uriDelimiter . ']' . $regExpTemp . ')*';
            }

            // parameter regExp
            $paramsRegExp[$param]['regExp'] = '#^' . $matches[4] . '$#i';
            $paramsRegExp[$param]['before'] = $before;
            $paramsRegExp[$param]['after'] = $after;
            $paramsRegExp[$param]['isArray'] = $isArrayParam;

            $regExpReplace = '(?<' . $param . '>' . $regExp . ')';

            if (!$isRequiredParam) {
                $before = $before ? '[\\' . $before . ']' : '';
                $after = $after ? '[\\' . $after . ']' : '';
                $regExpReplace = '(?:' . $before . $regExpReplace . $after . ')?';
            }

            return $regExpReplace;
        };

        // for parsing URL (ignore case)
        $pattern = '#^' . preg_replace_callback($regExpParam, $callback, $rule) . '$#i';

        // return
        return [
            'pattern' => $pattern,
            'allParams' => $allParams,
            'requiredParams' => $requiredParams,
            'paramsRegExp' => $paramsRegExp,
        ];
    }
}
