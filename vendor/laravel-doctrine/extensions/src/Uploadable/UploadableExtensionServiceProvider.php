<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Uploadable;

use Gedmo\Uploadable\UploadableListener;
use Illuminate\Support\ServiceProvider;

class UploadableExtensionServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton(UploadableListener::class);
    }
}
