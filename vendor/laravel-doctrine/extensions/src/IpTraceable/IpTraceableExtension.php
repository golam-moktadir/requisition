<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions\IpTraceable;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\IpTraceable\IpTraceableListener;
use Illuminate\Http\Request;
use LaravelDoctrine\Extensions\GedmoExtension;

class IpTraceableExtension extends GedmoExtension
{
    public function __construct(protected Request $request)
    {
    }

    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        $subscriber = new IpTraceableListener();
        $subscriber->setIpValue($this->request->getClientIp());

        $this->addSubscriber($subscriber, $manager);
    }

    /** @return mixed[] */
    public function getFilters(): array
    {
        return [];
    }
}
