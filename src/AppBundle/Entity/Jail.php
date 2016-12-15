<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Jail
 *
 * @ORM\Table(name="jail")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JailRepository")
 */
class Jail
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="until", type="datetime")
     */
    private $until;

    /**
     * @var int
     *
     * @ORM\Column(name="location", type="integer")
     */
    private $location;

    /**
     * @var bool
     *
     * @ORM\Column(name="breakable", type="boolean")
     */
    private $breakable = true;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set until
     *
     * @param \DateTime $until
     *
     * @return Jail
     */
    public function setUntil($until)
    {
        $this->until = $until;

        return $this;
    }

    /**
     * Get until
     *
     * @return \DateTime
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * Set time
     *
     * @param int $time
     *
     * @return Jail
     */
    public function setTime($time)
    {
        $now = new \DateTime;
        $now->add(new \DateInterval('PT' . $time . 'S'));

        $this->setUntil($now);

        return $this;
    }

    /**
     * Set location
     *
     * @param integer $location
     *
     * @return Jail
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return int
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set breakable
     *
     * @param boolean $breakable
     *
     * @return Jail
     */
    public function setBreakable($breakable)
    {
        $this->breakable = $breakable;

        return $this;
    }

    /**
     * Get breakable
     *
     * @return bool
     */
    public function getBreakable()
    {
        return $this->breakable;
    }

    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return Jail
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Jail
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
