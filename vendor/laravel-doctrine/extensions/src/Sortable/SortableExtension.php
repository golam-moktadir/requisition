<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Sortable;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sortable\SortableListener;
use LaravelDoctrine\Extensions\GedmoExtension;

class SortableExtension extends GedmoExtension
{
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new SortableListener();

        $this->addSubscriber($subscriber, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
