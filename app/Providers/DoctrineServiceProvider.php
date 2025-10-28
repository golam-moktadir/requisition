<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class DoctrineServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Connection::class, function ($app) {
            
            $config = [
                'driver'   => env('DB_CONNECTION', 'mysqli'),
                'host'     => env('DB_HOST', '127.0.0.1'),
                'dbname'   => env('DB_DATABASE', 'ecl_admin'),
                'user'     => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', '1234'),
            ];

            // You can add additional parameters here as needed (e.g., charset)
            return DriverManager::getConnection($config);
        });
    }

    public function boot()
    {
        //
    }
}
