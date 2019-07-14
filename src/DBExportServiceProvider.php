<?php

namespace OalidCse\DBExport;

use Illuminate\Support\ServiceProvider;

class DBExportServiceProvider extends ServiceProvider
{

    public function register()
    {
        // register controller
        $this->app->make('OalidCse\DBExport\DBExportController');
    }


    public function boot()
    {
        
    }
}
