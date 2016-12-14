<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\CrimeCommitEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

class CrimeSubscriber implements EventSubscriberInterface
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
            CrimeCommitEvent::NAME => [
                ['updateLastCrimeTime', 0]
            ]
        ];
    }

    public function updateLastCrimeTime(CrimeCommitEvent $event)
    {
        $this->user->setLastCrime(new \DateTime());
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }
}
