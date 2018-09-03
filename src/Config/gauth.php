<?php

return [
    // Open or Close Registration
    'registration_open'                      => true,

    // Name fields to Register, make sure it matches with your user model
    'registration_name_fields'               => [
        // 'name'
        'first_name',
        'last_name',
    ],

    // validation rules for registeration
    'registration_validateion_rules'         => [
        'first_name' => 'required|string',
        'last_name'  => 'required|string',
        'email'      => 'required|string|email|max:255|unique:users',
        'password'   => 'required|string|min:6|max:255|confirmed',
        'data'       => 'string'
    ],

    // Redirect path after user is successfulle logged in
    'redirect_path_after_login'              => '/home',

    // Redirect path after user is successfulle reseted password
    'redirect_path_after_password_reset'     => '/home',

    // Redirect path after user registered and email confiremed via mail
    'redirect_path_after_email_confirmation' => '/home',

    // User model must have a method $user->assignRole($roles)
    // in order to this works
    // And it must accept role slug, role id, roles array, role instance
    // NOTE -> make sure this role exist in database
    'default_roles'                          => ['subscriber'],

    // Email configuration to send welcome and confirmation(email) emails to user
    'email_from'                             => [
        'name'  => 'Gurinder Laravel Auth',
        'email' => 'noreply@example.com'
    ],

    // Previous Url config
    'previous_url'                           => [
        'name' => 'previousUrl'
    ],

    'social_providers' => [
        'enable'    => true,
        'available' => [
            'google'   => true,
            'twitter'  => true,
            'github'   => true,
            'facebook' => true,
        ]
    ]

];