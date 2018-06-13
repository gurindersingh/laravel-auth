<?php

namespace Gurinder\LaravelAuth\Repositories\Providers;


class GoogleSocialProvider extends BaseSocialProvider implements SocialProvider
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
        $userInfo = $this->getUserInfo($user);

        $data = [
            'name'           => $userInfo->getNames()[0]->givenName . ' ' . $userInfo->getNames()[0]->familyName,
            'first_name'     => $userInfo->getNames()[0]->givenName,
            'last_name'      => $userInfo->getNames()[0]->familyName,
            'email'          => $userInfo->getEmailAddresses()[0]->value,
            'email_verified' => true
        ];

        return $data;
    }

    /**
     * @param $user
     * @return \Google_Service_People_Person
     */
    protected function getUserInfo($user)
    {
        $client = new \Google_Client();
        $client->setApplicationName(config('app.name'));
        $client->setDeveloperKey(config('services.google.developer_key'));
        $client->setAccessToken(json_encode([
            'access_token'  => $user->token,
            'refresh_token' => $user->refreshToken,
            'expires_in'    => $user->expiresIn
        ]));

        $service = new \Google_Service_People($client);

        return $service->people->get('people/me', [
            'requestMask.includeField' => 'person.phone_numbers,person.names,person.email_addresses,person.photos'
        ]);
    }
}