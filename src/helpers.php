<?php

if (!function_exists('removeQueryFromUrl')) {

    /**
     *
     * Remove Query string from url
     *
     * @param string $url
     * @param string $concat
     * @return string
     */
    function removeQueryFromUrl($url, $concat = '')
    {
        $url = trim(str_replace(' ', '', strtolower($url)));

        $url = parse_url(filter_var($url, FILTER_SANITIZE_URL));

        return "{$url['scheme']}://{$url['host']}{$url['path']}{$concat}";
    }
}