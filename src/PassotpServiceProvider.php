<?php

namespace Momenshalaby\PassotpWhatsapp;

use Illuminate\Support\ServiceProvider;

class PassotpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/passotp.php', 'passotp');

        $this->app->singleton(WhatsappPassotpService::class, function () {
            return new WhatsappPassotpService();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/passotp.php' => config_path('passotp.php'),
        ], 'config');
    }
}
