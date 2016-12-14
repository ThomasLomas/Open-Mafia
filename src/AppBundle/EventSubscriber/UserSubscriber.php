<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Entity\Login;

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
                ['recordSuccessfulLogin', 0]
            ]
        ];
    }

    /**
     * Updates last login time for use in the online players page
     */
    public function updateLastOnline(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        // Only run once per page load
        if (!is_array($controller)) {
            return;
        }

        // User isn't logged in
        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            return;
        }

        // Update last login time
        $this->entityManager->getRepository('AppBundle:User')->updateLastOnline(
            $this->tokenStorage->getToken()->getUser()->getId()
        );

        return true;
    }

    /**
     * Records a successful login for the user
     */
    public function recordSuccessfulLogin(InteractiveLoginEvent $event)
    {
        $login = new Login;
        $login->setSuccessful(true);
        $login->setIp($event->getRequest()->getClientIp());
        $login->setUser($event->getAuthenticationToken()->getUser());

        $this->entityManager->persist($login);
        $this->entityManager->flush();

        return true;
    }
}
