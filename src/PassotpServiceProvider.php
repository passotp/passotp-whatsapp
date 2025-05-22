<?php

namespace Momenshalaby\PassotpWhatsapp;

use Illuminate\Support\ServiceProvider;

class PassotpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/passotp.php', 'passotp');

        $this->app->singleton(PassotpService::class, function () {
            return new PassotpService();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/passotp.php' => config_path('passotp.php'),
        ], 'config');
    }
}
