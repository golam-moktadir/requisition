<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interface\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\UserService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
