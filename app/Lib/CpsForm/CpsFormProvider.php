<?php

namespace App\Lib\CpsForm;

use Illuminate\Support\ServiceProvider;

class CpsFormProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('cpsformfacade', function () {
            return new CpsForm;
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('CpsForm', 'App\Lib\CpsForm\CpsFormFacade');
        });
    }
}
