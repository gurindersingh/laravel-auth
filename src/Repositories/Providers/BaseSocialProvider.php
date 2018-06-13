<?php

namespace Gurinder\LaravelAuth\Repositories\Providers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BaseSocialProvider
{

    protected $user;

    protected $data = [];

    protected $userModel;

    public function __construct()
    {
        if ($this->userModel = config('gauth.user_model') ?? config('auth.providers.users.model')) {
            if (class_exists($this->userModel)) {
                $this->userModel = new $this->userModel;
            }
        }
    }

    /**
     * @param $email
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    protected function userAlreadyInDatabase($email)
    {
        if ($user = $this->userModel->where('email', trim($email))->first()) {

            Auth::login($user);

            return redirect(config('gauth.redirect_path_after_login'));

        }

        return null;
    }

    /**
     * Valideate Email address
     *
     * @param $email
     * @return bool
     */
    protected function emailValid($email)
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email'
        ]);

        return $validator->fails() ? false : true;
    }

}