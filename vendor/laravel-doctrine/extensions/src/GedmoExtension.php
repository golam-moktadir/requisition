<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions;

use Doctrine\Common\EventManager;
use Doctrine\Common\EventSubscriber;
use LaravelDoctrine\ORM\Extensions\Extension as ExtensionContract;

abstract class GedmoExtension implements ExtensionContract
{
    protected function addSubscriber(EventSubscriber $subscriber, EventManager $manager): void
    {
        $manager->addEventSubscriber($subscriber);
    }
}
