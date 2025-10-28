<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\SoftDeletes;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use LaravelDoctrine\Extensions\GedmoExtension;

class SoftDeleteableExtension extends GedmoExtension
{
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new SoftDeleteableListener();

        $this->addSubscriber($subscriber, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [
            'soft-deleteable' => SoftDeleteableFilter::class,
        ];
    }
}
