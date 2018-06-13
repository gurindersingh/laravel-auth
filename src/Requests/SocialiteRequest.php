<?php

namespace Gurinder\LaravelAuth\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SocialiteRequest extends FormRequest
{
    /**
     * @var
     */
    protected $user;

    protected $userModel;

    /**
     * @var
     */
    protected $data;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @param $user
     * @param $provider
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function handleCallback($user, $provider)
    {
        $this->user = $user;

        $class = "Gurinder\LaravelAuth\Repositories\Providers\\" . ucwords("{$provider}SocialProvider");

        if (class_exists($class)) {

            return (new $class())->passwordChooserView($user);

        }

        abort(500, "No processing");


    }

}