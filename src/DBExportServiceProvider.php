<?php

namespace OalidCse\DBExport;

use Illuminate\Support\ServiceProvider;

class DBExportServiceProvider extends ServiceProvider
{

    public function register()
    {
        // register controller
        $this->app->make('OalidCse\DBExport\DBExportController');

        // register views
        $this->loadViewsFrom(__DIR__."/views", 'db_export');
    }


    public function boot()
    {
        // register routes
        $this->loadRoutesFrom(__DIR__."/routes.php");
    }
}
