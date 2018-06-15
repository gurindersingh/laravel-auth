<?php

namespace Gurinder\LaravelAuth\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Gurinder\LaravelAuth\Http\Controllers\Traits\Registerable;

class SocialiteRegistrationController extends Controller
{
    /*
     * Socialite User Register Controller
     *
     * This controller handles the registration of new users from
     * socialite providers as well as their validation and creation.
     */

    use Registerable;


    /**
     * Create a new controller instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest');

        $this->nameFields = config('gauth.registration_name_fields');

        $this->setRules(config('gauth.registration_validateion_rules'));

        $this->request = $request;

        $this->setDefaultRole();

        if ($this->userModel = config('gauth.user_model') ?? config('auth.providers.users.model')) {
            if (class_exists($this->userModel)) {
                $this->userModel = new $this->userModel;
            }
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View|mixed|null
     */
    public function register()
    {
        if ($response = $this->requestNotValid()) {
            return $response;
        }

        $decryptedData = json_decode(base64_decode(decrypt($this->request->data)));

        $data = [
            'email'          => $decryptedData->email,
            'email_verified' => $decryptedData->email_verified,
            'password'       => Hash::make($this->request->password)
        ];

        foreach ($this->nameFields as $nameField) {
            $data[$nameField] = $decryptedData->$nameField;
        }

        $user = $this->create($data);

        return $this->registered($user);
    }

    /**
     * Check Request is valid, die silently if $data not provided
     * as $data we need to perform decryption and check if email is tempered or not
     *
     * @return \Illuminate\Contracts\View\View|null
     */
    protected function requestNotValid()
    {
        $validator = $this->validator();

        if (!$this->request->data) {
            abort(500);
        }

        return $validator->fails() ? $this->choosePasswordView($validator, $this->request) : null;
    }


    /**
     * Decrypt data came from socialite
     * will return avatar, email, name
     *
     * @return $this
     */
    protected function decryptSocialiteData()
    {
        $decryptedData = json_decode(base64_decode(decrypt($this->request->data)));

        // if ($this->emailTempered($decryptedData->email, $this->request->email)) {
        //     abort(500);
        // }

        $this->emailVerified = $decryptedData->email_verified;

        // $this->avatarUrl = $decryptedData->avatar_url;

        return $this;
    }

    /**
     * Check if email from $provider is same as email from input
     *
     * @param $decryptedEmail
     * @param $requestEmail
     * @return bool
     */
    protected function emailTempered($decryptedEmail, $requestEmail)
    {
        return (bool)trim($decryptedEmail) != trim($requestEmail);
    }

    /**
     * Store user avatar
     *
     * @param $user
     */
    // protected function storeAvatar($user)
    // {
    //     try {
    //         if (filter_var($this->avatarUrl, FILTER_VALIDATE_URL) !== FALSE) {
    //             resolve(AvatarContract::class)->storeFromUrl($user, $this->avatarUrl);
    //         }
    //     } catch (\Exception $e) {
    //     }
    // }

    /**
     * @param         $validator
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    protected function choosePasswordView($validator, Request $request)
    {
        foreach ($this->nameFields as $nameField) {
            $data[$nameField] = $request->$nameField;
        }

        $data = $data + [
                'email'      => $request->email,
                'data'       => $request->data,
                'nameFields' => config('gauth.registration_name_fields')];

        return view('gauth::passwords.choose-password')->withErrors($validator)->with($data);

    }

}