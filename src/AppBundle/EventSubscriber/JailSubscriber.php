<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\JailEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

class JailSubscriber implements EventSubscriberInterface
{
    private $user;
    private $entityManager;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            JailEvent::BOOK => [
                ['persistJailTime', 255],
            ]
        ];
    }

    public function persistJailTime(JailEvent $event)
    {
        $this->entityManager->persist($event->getJail());
        $this->entityManager->flush();
    }
}
