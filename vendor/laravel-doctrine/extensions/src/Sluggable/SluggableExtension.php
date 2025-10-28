<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Sluggable;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\SluggableListener;
use LaravelDoctrine\Extensions\GedmoExtension;

class SluggableExtension extends GedmoExtension
{
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new SluggableListener();

        $this->addSubscriber($subscriber, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
