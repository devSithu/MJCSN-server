<?php

namespace App\Lib\CpsCSV;

use Illuminate\Support\ServiceProvider;

class CpsCSVProvider extends ServiceProvider
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

        $this->app->singleton('cpscsvfacade', function () {
            return new CpsCSV;
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('CpsCSV', 'App\Lib\CpsCSV\CpsCSVFacade');
        });
    }
}
