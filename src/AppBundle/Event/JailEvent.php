<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Jail;

class JailEvent extends Event
{
    const BOOK = 'jail.book';
    const BUST = 'jail.bust';
    const BREAK = 'jail.break';

    protected $jail;

    public function __construct(Jail $jail)
    {
        $this->jail = $jail;
    }

    public function getJail()
    {
        return $this->jail;
    }
}
