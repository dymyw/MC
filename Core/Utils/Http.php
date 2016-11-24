<?php
/**
 * Http utils
 *
 * @package Core_Utils
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-24
 */

namespace Core\Utils;

class Http
{
    /**
     * If current page is using HTTPS protocol, it returns true
     *
     * @return bool
     */
    public static function isHttps()
    {
        return
            (isset($_SERVER['HTTP_X_SERVER_PORT']) && $_SERVER['HTTP_X_SERVER_PORT'] == 443)
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    }

    /**
     * @return bool
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
               && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    /**
     * @param string $type
     * @param string|null $charset
     * @example
     *  Http::mimeType('html', 'utf-8');
     *  Http::mimeType('exe');
     */
    public static function mimeType($type, $charset = null)
    {
        $types = [
            'html'  => 'text/html',
            'xml'   => 'text/xml',
            'css'   => 'text/css',
            'txt'   => 'text/plain',
            'json'  => 'application/json',
            'jsonp' => 'application/javascript',
            'js'    => 'application/javascript',
            'rss'   => 'application/rss+xml',
            'atom'  => 'application/atom+xml',
            'xhtml' => 'application/xhtml+xml',

            'gif'   => 'image/gif',
            'jpg'   => 'image/jpeg',
            'png'   => 'image/png',
            'ico'   => 'image/x-icon',
            'bmp'   => 'image/x-ms-bmp',
            'flv'   => 'video/x-flv',
            'doc'   => 'application/msword',
            'xls'   => 'application/vnd.ms-excel',
            'csv'   => 'application/vnd.ms-excel',
            'pdf'   => 'application/pdf',
            'zip'   => 'application/zip',
            'rar'   => 'application/x-rar-compressed',
            'swf'   => 'application/x-shockwave-flash',
            '*'     => 'application/octet-stream',
        ];

        if (!isset($types[$type])) {
            $type = '*';
        }

        $contentType = 'Content-Type: ' . $types[$type];
        if ($charset) {
            $contentType .= '; charset=' . $charset;
        }
        header($contentType);
    }

    /**
     * Content-Disposition header
     *
     * @param string $filename
     */
    public static function disposition($filename)
    {
        header('Content-Disposition: attachment; filename=' . $filename);
    }
}
