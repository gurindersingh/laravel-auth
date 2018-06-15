<?php

namespace Gurinder\LaravelAuth\Http\Controllers;


use Illuminate\Foundation\Auth\RegistersUsers;
use Gurinder\LaravelAuth\Http\Controllers\Traits\Registerable;

class RegisterController extends Controller
{

    use RegistersUsers, Registerable {
        Registerable::register insteadof RegistersUsers;
        Registerable::registered insteadof RegistersUsers;
    }

    public function __construct()
    {
        $this->nameFields = config('gauth.registration_name_fields');

        $this->setRules(config('gauth.registration_validateion_rules'));

        if ($this->userModel = config('gauth.user_model') ?? config('auth.providers.users.model')) {
            if (class_exists($this->userModel)) {
                $this->userModel = new $this->userModel;
            }
        }
    }

    public function showRegistrationForm()
    {
        if (is_array($this->nameFields)) {

            $nameFields = $this->nameFields;

            return view('gauth::register', compact('nameFields'));
        }

        abort(500, "Name field is not present");

    }


}