<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Library\DatatableExporter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        
        // Bind DatatableExporter as a singleton
        $this->app->singleton(DatatableExporter::class, function ($app) {
            return DatatableExporter::getInstance();
        });        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
