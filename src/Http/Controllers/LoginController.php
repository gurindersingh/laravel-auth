<?php

namespace Gurinder\LaravelAuth\Http\Controllers;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return view('gauth::login');
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function redirectTo()
    {
        if (request()->exists(config('gauth.previous_url.name'))) {
            return url(request()->get(config('gauth.previous_url.name')));
        }

        return config('gauth.redirect_path_after_login');
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'email_verified' => $user->email_verified,
            ]);
        }
    }

}