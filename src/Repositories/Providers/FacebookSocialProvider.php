<?php

namespace Gurinder\LaravelAuth\Repositories\Providers;


class FacebookSocialProvider extends BaseSocialProvider implements SocialProvider
{

    /**
     * Return the view to choose password with encrpted data for registeration
     *
     * @param $user
     * @return mixed
     */
    public function passwordChooserView($user)
    {
        $data = $this->getDataForRegisteration($user);

        if ($redirect = $this->userAlreadyInDatabase($data['email'])) {
            return $redirect;
        }

        if ($this->emailValid($data['email'])) {

            $data = $data + [
                    'email'      => $data['email'],
                    'data'       => encrypt(base64_encode(json_encode($data))),
                    'nameFields' => config('gauth.registration_name_fields')];

            return view('gauth::passwords.choose-password')->with($data);


        }

        abort(403, 'Email not provided');
    }

    /**
     * @param $user
     * @return array
     */
    protected function getDataForRegisteration($user)
    {
        $name = collect(explode(" ", $user->getName()));

        $data = [
            'name'           => $user->getName(),
            'first_name'     => $name->first(),
            'last_name'      => $name->last(),
            'email'          => $user->getEmail(),
            'email_verified' => true
        ];

        return $data;
    }
}