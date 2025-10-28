<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Uploadable;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Uploadable\UploadableListener;
use LaravelDoctrine\Extensions\GedmoExtension;

class UploadableExtension extends GedmoExtension
{
    public function __construct(protected UploadableListener $listener)
    {
    }

    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $this->addSubscriber($this->listener, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
