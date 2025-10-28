<?php

declare(strict_types=1);

namespace LaravelDoctrine\Extensions;

use Doctrine\Common\EventSubscriber;
use Illuminate\Contracts\Auth\Guard;

use function app;
use function call_user_func;
use function call_user_func_array;
use function get_class;

class ResolveUserDecorator implements EventSubscriber
{
    public function __construct(private EventSubscriber $wrapped, private string $userSetterMethod)
    {
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return $this->wrapped->getSubscribedEvents();
    }

    /** @param mixed[] $params */
    public function __call(string $method, array $params): mixed
    {
        if ($method !== 'loadClassMetadata' && $this->getGuard()->check()) {
            call_user_func([$this->wrapped, $this->userSetterMethod], $this->getGuard()->user());
        }

        return call_user_func_array([$this->wrapped, $method], $params);
    }

    /**
     * Get the namespace of extension event subscriber.
     * used for cache id of extensions also to know where
     * to find Mapping drivers and event adapters
     */
    protected function getNamespace(): string
    {
        return $this->wrapped->getNamespace();
    }

    /**
     * Get the class of extension event subscriber.
     * Used to identify which event subscriber is wrapped by the resolver.
     */
    public function getEventSubscriberClass(): string
    {
        return get_class($this->wrapped);
    }

    /**
     * Get current Auth manager.
     */
    protected function getGuard(): Guard
    {
        return app('auth')->guard();
    }
}
