<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\SecurityEvents;

class UserSubscriber implements EventSubscriberInterface
{
    private $authorizationChecker;
    private $tokenStorage;
    private $entityManager;

    public function __construct(AuthorizationChecker $authorizationChecker, TokenStorage $tokenStorage, EntityManager $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['updateLastOnline', 0]
            ],
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['recordLogin', 0]
            ]
        ];
    }

    public function updateLastOnline(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        // User isn't logged in
        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        $update = $this->entityManager->createQuery('
            UPDATE AppBundle:User u SET u.lastOnline = :now WHERE u.id = :id
        ');

        $update->execute([
            'now' => new \DateTime,
            'id' => $user->getId()
        ]);

        return true;
    }

    public function recordLogin()
    {

    }
}
