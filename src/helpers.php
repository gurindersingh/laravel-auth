<?php

if (!function_exists('generateEmailConfirmationTokenForUser')) {

    /**
     * @param $user
     * @return mixed
     */
    function generateEmailConfirmationTokenForUser($user)
    {
        $tokenString = $user->email . '-' . str_random(20) . '-' . time() . $user->id;

        $user->update([
            'email_verification_token' => hash_hmac('sha256', $tokenString, config('app.key'))
        ]);

        return $user->fresh();
    }

}


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