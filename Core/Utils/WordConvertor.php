<?php
/**
 * WordConvertor utils
 *
 * @package Core_Utils
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-10-09
 * @version 2016-11-03
 */

namespace Core\Utils;

class WordConvertor
{
    /**
     * @param string $str
     * @param bool $lowerFirst
     * @return string
     */
    public static function dashToCamelCase($str, $lowerFirst = false)
    {
        return self::separatorToCamelCase($str, '-', $lowerFirst);
    }

    /**
     * @param string $str
     * @param bool $lowerFirst
     * @return string
     */
    public static function underscoreToCamelCase($str, $lowerFirst = false)
    {
        return self::separatorToCamelCase($str, '_', $lowerFirst);
    }

    /**
     * @param string $str
     * @param bool $toLower
     * @return string
     */
    public static function camelCaseToDash($str, $toLower = true)
    {
        return self::camelCaseToSeparator($str, '-', $toLower);
    }

    /**
     * @param string $str
     * @param bool $toLower
     * @return string
     */
    public static function camelCaseToUnderscore($str, $toLower = true)
    {
        return self::camelCaseToSeparator($str, '_', $toLower);
    }

    /**
     * @param string $str
     * @param char|char[] $separator
     * @param bool $lowerFirst
     * @return string
     */
    public static function separatorToCamelCase($str, $separator = ['-', '_'], $lowerFirst = false)
    {
       $replaceWhiteSpaces = str_replace($separator, ' ', $str);
       $str = preg_replace('/\s+/', '', ucwords($replaceWhiteSpaces));
       if ($lowerFirst) {
           $str{0} = strtolower($str{0});
       }

       return $str;
    }

    /**
     * @param string $str
     * @param char $separator
     * @param bool $toLower
     * @return string
     */
    public static function camelCaseToSeparator($str, $separator = '-', $toLower = true)
    {
        $str = ltrim(preg_replace("/\p{Lu}/", $separator . '\0', $str), $separator);
        if ($toLower) {
            $str = strtolower($str);
        }

        return $str;
    }
}
