<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class JailEvent extends Event
{
    const BOOK = 'jail.book';
    const BUST = 'jail.bust';
    const BREAK = 'jail.break';

    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
