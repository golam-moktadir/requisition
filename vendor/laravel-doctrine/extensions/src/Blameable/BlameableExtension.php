<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Blameable;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Blameable\BlameableListener;
use LaravelDoctrine\Extensions\GedmoExtension;
use LaravelDoctrine\Extensions\ResolveUserDecorator;

class BlameableExtension extends GedmoExtension
{
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new ResolveUserDecorator(
            new BlameableListener(),
            'setUserValue',
        );

        $this->addSubscriber($subscriber, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
