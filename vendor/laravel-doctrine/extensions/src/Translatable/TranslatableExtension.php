<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\Translatable;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\TranslatableListener;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use LaravelDoctrine\Extensions\GedmoExtension;

class TranslatableExtension extends GedmoExtension
{
    public function __construct(protected Application $application, protected Repository $repository, private Dispatcher $events)
    {
    }

    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new TranslatableListener();
        $subscriber->setTranslatableLocale($this->application->getLocale());
        $subscriber->setDefaultLocale($this->repository->get('app.locale'));

        $this->addSubscriber($subscriber, $manager);

        $this->events->listen('locale.changed', static function ($locale) use ($subscriber): void {
            $subscriber->setTranslatableLocale($locale);
        });
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
