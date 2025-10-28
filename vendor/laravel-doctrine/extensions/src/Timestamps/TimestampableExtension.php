<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Timestamps;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Timestampable\TimestampableListener;
use LaravelDoctrine\Extensions\GedmoExtension;

class TimestampableExtension extends GedmoExtension
{
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new TimestampableListener();

        $this->addSubscriber($subscriber, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
