<?php

namespace Jhonoryza\DatabaseLogger;

use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/logging-db.php', 'logging'
        );
    }

    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/logging-db.php' => config_path('logging-db.php'),
        ], 'laravel-database-logger');
    }
}
