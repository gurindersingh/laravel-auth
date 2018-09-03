<?php

namespace Gurinder\LaravelAuth;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Gurinder\LaravelAuth\Middlewares\ConfirmedEmail;

class AuthServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__ . '/Config/gauth.php' => config_path('gauth.php')], 'gauth::config');

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/views', 'gauth');

        $this->publishes([__DIR__ . '/views' => $this->app->resourcePath('views/vendor/acl')], 'gauth::views');

        $this->app['router']->aliasMiddleware('confirmedEmail', ConfirmedEmail::class);
    }

    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/Config/gauth.php', 'gauth');

        $this->registerBladeExtensions();

    }

    protected function registerBladeExtensions() 
    {

        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {

            $bladeCompiler->directive('registerationOpen', function () {
                return "<?php if (!auth()->check() && registerationOpen() ? true : false): ?>";
            });

            $bladeCompiler->directive('endRegisterationOpen', function () {
                return '<?php endif; ?>';
            });

        });

    }

}