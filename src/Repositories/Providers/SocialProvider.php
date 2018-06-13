<?php

namespace Gurinder\LaravelAuth\Repositories\Providers;


interface SocialProvider
{

    /**
     * Return the view to choose password with encrpted data for registeration
     *
     * @param $user
     * @return mixed
     */
    public function passwordChooserView($user);

}