<?php

namespace Gurinder\LaravelAuth\Middlewares;


use Gurinder\LaravelAuth\Exceptions\UnverifiedEmailException;

class ConfirmedEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \Closure                  $next
     * @return mixed
     * @throws UnverifiedEmailException
     */
    public function handle($request, \Closure $next)
    {
        if ($request->user() && !$request->user()->email_verified) {
            \Auth::logout();
            throw new UnverifiedEmailException();
        }

        if (is_null($request->user())) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}