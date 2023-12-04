<?php

namespace EdLugz\SasaPay;

use Illuminate\Support\ServiceProvider;

class SasaPayServiceProvider extends ServiceProvider
{
    /**
     * Package path to config.
     */
    const CONFIG_PATH = __DIR__.'/../config/sasapay.php';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('sasapay.php'),
        ], 'config');
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'edlugz');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'edlugz');
        //$this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sasapay.php', 'sasapay');

        // Register the service the package provides.
        $this->app->singleton('sasapay', function ($app) {
            return new SasaPay();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sasapay'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/sasapay.php' => config_path('sasapay.php'),
        ], 'sasapay.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/edlugz'),
        ], 'sasapay.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/edlugz'),
        ], 'sasapay.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/edlugz'),
        ], 'sasapay.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
