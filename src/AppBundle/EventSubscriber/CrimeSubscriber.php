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
            CrimeCommitEvent::SUCCESS => [
                ['giveExperience', 255],
                ['updateLastCrimeTime', 0],
                ['giveCash', 0],
                ['increaseCrimeChance', 0],
                ['flushDatabase', -255],
            ],
            CrimeCommitEvent::FAIL => [
                ['giveExperience', 255],
                ['updateLastCrimeTime', 0],
                ['increaseCrimeChance', 0],
                ['flushDatabase', -255],
            ]
        ];
    }

    public function updateLastCrimeTime(CrimeCommitEvent $event)
    {
        $this->user->setLastCrime(new \DateTime());
        $this->entityManager->persist($this->user);
    }

    public function giveCash(CrimeCommitEvent $event)
    {
        $cash = mt_rand(50,100) * ($event->getCrime()->getCrime() + 1);

        $this->user->setCash(
            $this->user->getCash() + $cash
        );

        $this->entityManager->persist($this->user);
    }

    public function giveExperience(CrimeCommitEvent $event)
    {
        $experience = mt_rand(1, 10) * ($event->getCrime()->getCrime() + 1);

        $this->user->setExperience(
            $this->user->getExperience() + $experience
        );

        $this->entityManager->persist($this->user);
    }

    public function increaseCrimeChance(CrimeCommitEvent $event)
    {
        $crimeCommitted = $event->getCrime()->getCrime();
        $chance = ceil(mt_rand(1, 10) / ceil(($crimeCommitted + 1) / 2));
        $crimeChances = $this->user->getCrimeChances();

        $existingChance = (isset($crimeChances[$crimeCommitted])) ? $crimeChances[$crimeCommitted] : 0;

        $crimeChances[$crimeCommitted] = $existingChance + $chance;

        if($crimeChances[$crimeCommitted] > 100) {
            $crimeChances[$crimeCommitted] = 100;
        }

        $this->user->setCrimeChances($crimeChances);
        $this->entityManager->persist($this->user);
    }

    public function flushDatabase()
    {
        $this->entityManager->flush();
    }
}
