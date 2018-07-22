<?php

namespace Gurinder\LaravelAuth\Http\Controllers;


use Google_Service_People;
use Laravel\Socialite\Facades\Socialite;
use Gurinder\LaravelAuth\Requests\SocialiteRequest;

class SocialiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    /**
     * @param $provider
     * @return mixed
     */
    public function toProvider($provider)
    {
        if (!registerationOpen()) {
            abort(403, "Registeration is closed at the moment");
        }

        $driver = Socialite::driver($provider);

        if ($previousUrl = $this->hasPrevoiusUrl() && method_exists($driver, 'with')) {
            $driver = $driver->with([
                'previous_url' => $previousUrl
            ]);
        }


        return $provider == 'google' ?
            $driver->scopes(['openid', 'profile', 'email', Google_Service_People::CONTACTS_READONLY])->redirect() :
            $driver->redirect();

    }

    /**
     * @param SocialiteRequest $request
     * @param                  $provider
     * @return mixed
     */
    public function fromProvider(SocialiteRequest $request, $provider)
    {
        if (!registerationOpen()) {
            abort(403, "Registeration is closed at the moment");
        }

        $driver = Socialite::driver($provider);

        $user = !$this->hasInvalidState() ? $driver->stateless()->user() : $driver->user();

        return $request->handleCallback($user, $provider);
    }

    /**
     * Determine if the current request / session has a mismatching "state".
     * @return bool
     */
    protected function hasInvalidState()
    {
        $state = request()->getSession()->pull('state');
        return !(strlen($state) > 0 && request()->input('state') === $state);
    }

    public function hasPrevoiusUrl()
    {
        if (request()->exists(config('gauth.previous_url.name'))) {
            return request()->get(config('gauth.previous_url.name'));
        }

        return null;
    }

}