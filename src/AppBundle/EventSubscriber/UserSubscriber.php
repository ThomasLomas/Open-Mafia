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
use AppBundle\Event\CrimeCommitEvent;

class UserSubscriber implements EventSubscriberInterface
{
    private $openMafia;
    private $authorizationChecker;
    private $tokenStorage;
    private $entityManager;

    public function __construct(array $openMafia, AuthorizationChecker $authorizationChecker, TokenStorage $tokenStorage, EntityManager $entityManager)
    {
        $this->openMafia = $openMafia;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['removeOldJailRecords', 0]
            ],
            KernelEvents::CONTROLLER => [
                ['updateLastOnline', 0]
            ],
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['recordSuccessfulLogin', 0],
                ['updateUserRank', -255]
            ],
            CrimeCommitEvent::SUCCESS => [
                ['updateUserRank', -255]
            ],
            CrimeCommitEvent::FAIL => [
                ['updateUserRank', -254]
            ],
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
    }

    /**
     * Updates the user rank on events that changes EXP
     */
    public function updateUserRank()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        // No point if the user has no experience
        if($user->getExperience() === 0) {
            return;
        }

        foreach($this->openMafia['ranks'] as $index => $rank) {
            if($user->getExperience() <= $rank['experience']) {
                $user->setRank($index - 1);
                break;
            }
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Immedietely remove jail records for a player that is online instead of waiting
     * for the console command to clear it up
     */
    public function removeOldJailRecords()
    {
        // User isn't logged in
        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            return;
        }

        $this->entityManager->getRepository('AppBundle:Jail')->removeOldRecords(
            $this->tokenStorage->getToken()->getUser()->getId()
        );
    }
}
