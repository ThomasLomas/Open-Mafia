<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\JailEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JailSubscriber implements EventSubscriberInterface
{
    private $router;
    private $annotationReader;
    private $user;
    private $entityManager;

    public function __construct(Router $router, Reader $annotationReader, TokenStorage $tokenStorage, EntityManager $entityManager)
    {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            JailEvent::BOOK => [
                ['persistJailTime', 255],
            ],
            KernelEvents::CONTROLLER => [
                ['checkInJail', 0]
            ]
        ];
    }

    public function persistJailTime(JailEvent $event)
    {
        $this->entityManager->persist($event->getJail());
        $this->entityManager->flush();
    }

    public function checkInJail(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        list($controllerObject, $methodName) = $controller;

        $methodAnnotation = $this->annotationReader->getMethodAnnotation(
            new \ReflectionMethod(
                ClassUtils::getClass($controllerObject),
                $methodName
            ),
            'AppBundle\Annotation\CheckJail'
        );

        if(!$methodAnnotation) {
            return;
        }

        // This user is in Jail
        // Alter response to redirect
        if($this->entityManager->getRepository('AppBundle:Jail')->getActiveJail($this->user)) {
            $route = $this->router->generate('jail_list', [], UrlGeneratorInterface::ABSOLUTE_PATH);

            $event->setController(
                function() use ($route) {
                    return new RedirectResponse($route, 302);
                }
            );
        }
    }
}
