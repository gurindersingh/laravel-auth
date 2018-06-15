<?php

use \Illuminate\Support\Facades\Route;

Route::namespace('Gurinder\LaravelAuth\Http\Controllers')->group(function () {

    // Login
    Route::get('login', 'LoginController@showLoginForm')->name('login')->middleware(['web', 'guest']);
    Route::post('login', 'LoginController@login')->middleware(['web', 'guest']);

    // Login
    Route::post('logout', 'LoginController@logout')->name('logout')->middleware(['web']);

    // Password Reset
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')
        ->name('password.request')
        ->middleware(['web', 'guest']);
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')
        ->name('password.email')
        ->middleware(['web', 'guest']);
    Route::post('password/reset', 'ResetPasswordController@reset')
        ->middleware(['web', 'guest']);
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')
        ->name('password.reset')
        ->middleware(['web', 'guest']);

    // Register
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register')->middleware(['web', 'guest']);
    Route::post('register', 'RegisterController@register')->middleware(['web', 'guest']);

    Route::get('email-confirmation/resend', 'EmailVerificationController@show')
        ->name('email.confirmation.form')->middleware(['web', 'guest']);

    Route::post('email-confirmation/resend', 'EmailVerificationController@create')
        ->name('email.confirmation.create')->middleware(['throttle:10,5', 'web', 'guest']);

    Route::get('email-confirmation/{data}', 'EmailVerificationController@confirm')
        ->name('email.confirmation.confirm')->middleware(['web', 'guest']);

    // Socialite
    Route::get('auth-by/{provider}', 'SocialiteController@toProvider')
        ->name('socialite.to.provider')
        ->middleware(['web', 'guest']);
    Route::get('socialite/{provider}/callback', 'SocialiteController@fromProvider')
        ->name('socialite.from.provider')
        ->middleware(['web', 'guest']);
    Route::post('social/register', 'SocialiteRegistrationController@register')
        ->name('socialite.register')
        ->middleware(['web', 'guest']);

});

