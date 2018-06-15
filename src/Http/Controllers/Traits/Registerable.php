<?php

namespace Gurinder\LaravelAuth\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Gurinder\LaravelAuth\Repositories\SendToUrl;
use Gurinder\LaravelAuth\Notifications\ConfirmEmail;
use Gurinder\LaravelAuth\Notifications\WelcomeEmail;

trait Registerable
{

    /**
     * @var
     */
    protected $rules;

    /**
     * @var
     */
    protected $request;

    /**
     * @var
     */
    protected $userModel;

    /**
     * @var
     */
    protected $nameFields;

    /**
     * @var null
     */
    protected $avatarUrl = null;

    /**
     * @var null
     */
    protected $defaultRole = null;

    /**
     * @var bool
     */
    protected $emailVerified = false;

    /**
     * @param bool $emailVerified
     */
    public function setEmailVerified(bool $emailVerified)
    {
        $this->emailVerified = $emailVerified;
    }

    /**
     * @param null $defaultRole
     * @return $this
     */
    public function setDefaultRole($defaultRole = null)
    {
        $this->defaultRole = $defaultRole ?? config('gauth.default_roles');

        return $this;
    }

    /**
     * @param mixed $userModel
     * @return Registerable
     */
    public function setUserModel($userModel)
    {
        $this->userModel = $userModel;

        return $this;
    }

    /**
     * @param mixed $rules
     * @return Registerable
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        $this->rules['data'] = 'string';

        return $this;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->request = $request;

        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($user->email_verified) {
            $this->guard()->login($user);
        }

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     * @param null $data
     * @return mixed
     */
    protected function create($data = null)
    {

        $user = $this->userModel->create($this->getAttributes($data));

        if (method_exists($user, 'assignRole') && $this->defaultRole) {
            $user->assignRole($this->defaultRole);
        }

        $user->email_verified ? $user->notify(new WelcomeEmail()) : $user->notify(new ConfirmEmail());

        return $user;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator()
    {
        return Validator::make($this->request->all(), $this->rules);
    }

    /**
     * The user has been registered.
     *
     * @param  mixed $user
     * @return mixed
     */
    protected function registered($user)
    {
        if ($user->email_verified) {

            auth()->login($user);

            return redirect(config('gauth.redirect_path_after_email_confirmation'));
        }

        return redirect()->route('email.confirmation.form');
    }

    /**
     * @return bool
     */
    protected function registerationOpen()
    {
        return (boolean)config('gauth.registration_open', true);
    }

    /**
     * @param null $data
     * @return array|null
     */
    protected function getAttributes($data = null)
    {
        if (!$data) {
            $data = [
                'email'          => $this->request->email,
                'password'       => Hash::make($this->request->password),
                'email_verified' => $this->emailVerified
            ];

            foreach ($this->nameFields as $nameField) {
                $data[$nameField] = $this->request->$nameField;
            }
        }

        $data['email_verification_token'] = optional($data)['email_verified'] ? null : str_random(15) . time();

        unset($data['password_confirmation']);

        $data['password'] = Hash::needsRehash($data['password']) ? Hash::make($data['password']) : $data['password'];

        return $data;
    }

}