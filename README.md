# LaravelDBExport

A simple mysql database export package for laravel.

  - You can export your database easily in laravel by using this package
  - For Security perpose you can set whitlist ip addresses to set limited access

# Installation
Install the package through [Composer](http://getcomposer.org/). 
Run the Composer require command from the Terminal:
    
    composer require oalid-cse/laravel-db-export

If you are using Laravel `5.5` or updated version, this is all there is to do for installation.

If you still using on version `5.4` or less of Laravel, the final steps for you are to add the service provider of the package and alias the package. To do this open your `config/app.php` file.

Add the following line to the `providers` array:

	OalidCse\DBExport\DBExportServiceProvider::class,

And optionally add the following line to the `aliases` array:

    'DBExport' => OalidCse\DBExport\DBExportController::class,
    
Now you're ready to start using the laravel-db-export in your application.


# Uses

The LaravelDBExport gives you database content. You just need to download it.

Your Content:

    $content = DBExport::export_database();

For Download:

    return response()->download($content);

If you need to customize the database name then use:

    return response()->download($content, 'custom-name.sql');
    
#### Export Database Example:
In your route `routes/web.php` use:

    Route::get('/your-route', function(){
        $content = DBExport::export_database();
        return response()->download($content, 'demo.sql');
    });


#### Set Whitelist IP's

For whitelist ip in your `.env` file use `DB_EXPORT_VALID_IPS` variable.
Seperate all ip using comma ","
##### example: 

    DB_EXPORT_VALID_IPS:"192.168.1.1,192.168.1.2,192.168.1.3"


`Happy Coding :)`
