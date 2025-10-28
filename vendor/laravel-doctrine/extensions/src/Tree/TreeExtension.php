<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Tree;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\TreeListener;
use LaravelDoctrine\Extensions\GedmoExtension;

class TreeExtension extends GedmoExtension
{
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new TreeListener();

        $this->addSubscriber($subscriber, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
