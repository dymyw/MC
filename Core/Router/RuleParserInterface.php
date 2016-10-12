<?php
/**
 * RuleParser interface
 *
 * @package Core_Router
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-09-15
 * @version 2016-10-09
 */

namespace Core\Router;

interface RuleParserInterface
{
    /**
     * Set URI delimiter
     *
     * @param char $delimiter
     * @return RuleParserInterface
     * @throws \InvalidArgumentException
     */
    public function setUriDelimiter($delimiter);

    /**
     * Get URI delimiter
     *
     * @return char
     */
    public function getUriDelimiter();

    /**
     * Set the pairs identifier
     *
     * @param string $identifier
     * @return RuleParserInterface
     */
    public function setPairsIdentifier($identifier);

    /**
     * Get the pairs identifier
     *
     * @return string
     */
    public function getPairsIdentifier();

    /**
     * Set the router rules
     *
     * @param array $rules
     * @return RuleParserInterface
     */
    public function setRules(array $rules);

    /**
     * Get the parsed rules
     *
     * @return array
     */
    public function getParsedRules();
}
