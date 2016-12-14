<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CrimeCommitEvent extends Event
{
    const NAME = 'crime.commit';

    protected $crime;

    public function __construct(array $crime)
    {
        $this->crime = $crime;
    }

    public function getCrime()
    {
        return $this->crime;
    }
}
