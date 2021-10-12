<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\EventDispatcher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class LazyEventDispatcher implements EventDispatcherInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher = null;

    /**
     * @param ContainerInterface $container
     * @param string             $serviceId
     */
    public function __construct($container, $serviceId)
    {
        $this->container = $container;
        $this->serviceId = $serviceId;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        return $this->getEventDispatcher()->dispatch($event, $eventName);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener(string $eventName, callable $listener, int $priority = 0)
    {
        $this->getEventDispatcher()->addListener($eventName, $listener, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->addSubscriber($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener(string $eventName, callable $listener)
    {
        $this->getEventDispatcher()->removeListener($eventName, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->removeSubscriber($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners(string $eventName = null)
    {
        return $this->getEventDispatcher()->getListeners($eventName);
    }

    /**
     * {@inheritdoc}
     */
    public function getListenerPriority(string $eventName, callable $listener)
    {
        return $this->getEventDispatcher()->getListenerPriority($eventName, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners(string $eventName = null)
    {
        return $this->getEventDispatcher()->hasListeners($eventName);
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        if (null === $this->eventDispatcher) {
            $this->eventDispatcher = $this->container->get($this->serviceId);
        }

        return $this->eventDispatcher;
    }
}
