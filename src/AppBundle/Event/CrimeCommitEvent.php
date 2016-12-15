<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Model\Crime;

class CrimeCommitEvent extends Event
{
    const SUCCESS = 'crime.success';
    const FAIL = 'crime.fail';

    protected $crime;

    public function __construct(Crime $crime)
    {
        $this->crime = $crime;
    }

    public function getCrime()
    {
        return $this->crime;
    }
}
