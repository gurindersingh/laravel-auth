<?php

namespace Gurinder\LaravelAuth\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;


    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string|null              $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('gauth::passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function redirectTo()
    {
        return config('gauth.redirect_path_after_password_reset', '/home');
    }
}