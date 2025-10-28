<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions;

use Gedmo\DoctrineExtensions;
use Illuminate\Support\ServiceProvider;

class GedmoExtensionsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app['events']->listen('doctrine.extensions.booting', function (): void {
            $registry = $this->app->make('registry');

            foreach ($registry->getManagers() as $manager) {
                $chain = $manager->getConfiguration()->getMetadataDriverImpl();

                if ($this->needsAllMappings()) {
                    DoctrineExtensions::registerMappingIntoDriverChainORM($chain);
                } else {
                    DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($chain);
                }
            }
        });
    }

    private function needsAllMappings(): mixed
    {
        return $this->app->make('config')->get('doctrine.gedmo.all_mappings', false) === true;
    }
}
