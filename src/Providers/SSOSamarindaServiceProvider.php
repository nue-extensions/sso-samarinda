<?php

namespace Nue\SSOSamarinda\Providers;

use Illuminate\Support\ServiceProvider;
use Novay\Nue\Nue;
use Nue\SSOSamarinda\SSOSamarinda;
use Nue\SSOSamarinda\Http\Middleware\SSOAutoLogin;

class SSOSamarindaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * {@inheritdoc}
     */
    public function boot(SSOSamarinda $extension)
    {
        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'nue-sso-samarinda');
        }

        $this->app->booted(function() use ($extension) {
            $extension->routes(__DIR__.'/../../routes/web.php');
        });

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        app('router')->pushMiddlewareToGroup('web', SSOAutoLogin::class);

        $extension->boot();
    }
}