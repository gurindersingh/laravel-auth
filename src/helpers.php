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

if (!function_exists('registerationOpen')) {

    /**
     *
     * Check if registeration is open or closed
     *
     * @param boolean $default
     * @return boolean
     */
    function registerationOpen($default = true)
    {
        $default = config('gauth.registration_open', $default);

        return (boolean) config('auth.registeration_open', $default);
    }
}