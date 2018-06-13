<?php

namespace Gurinder\LaravelAuth;


use Gurinder\LaravelAuth\Middlewares\ConfirmedEmail;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__ . '/Config/gauth.php' => config_path('gauth.php')], 'gauth::config');

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/Views', 'gauth');

        $this->publishes([__DIR__ . '/Views' => $this->app->resourcePath('views/vendor/acl')], 'gauth::views');

        $this->app['router']->aliasMiddleware('ConfirmedEmail', ConfirmedEmail::class);
    }

    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/Config/gauth.php', 'gauth');

    }

}