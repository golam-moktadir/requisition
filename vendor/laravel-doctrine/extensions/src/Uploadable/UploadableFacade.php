<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Uploadable;

use Gedmo\Uploadable\UploadableListener;
use Illuminate\Support\Facades\Facade;

class UploadableFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return UploadableListener::class;
    }
}
