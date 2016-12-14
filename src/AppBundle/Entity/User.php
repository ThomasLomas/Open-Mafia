<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
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
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    private $cash = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $rank = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $experience = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $location = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $lastOnline;

    /**
     * @ORM\OneToMany(targetEntity="Login", mappedBy="user")
     */
    private $logins;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $crimeChances = [];

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $gtaChances = [];

    /**
     * @var \DateTime
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $lastCrime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $lastGta;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logins = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get salt
     * Not required due to encryption algorithm
     *
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return boolean
     */
    public function eraseCredentials()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Set cash
     *
     * @param float $cash
     *
     * @return User
     */
    public function setCash($cash)
    {
        $this->cash = $cash;

        return $this;
    }

    /**
     * Get cash
     *
     * @return float
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return User
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set experience
     *
     * @param integer $experience
     *
     * @return User
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return integer
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set location
     *
     * @param integer $location
     *
     * @return User
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return integer
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set lastOnline
     *
     * @param \DateTime $lastOnline
     *
     * @return User
     */
    public function setLastOnline(\DateTime $lastOnline)
    {
        $this->lastOnline = $lastOnline;

        return $this;
    }

    /**
     * Get lastOnline
     *
     * @return \DateTime
     */
    public function getLastOnline()
    {
        return $this->lastOnline;
    }

    /**
     * Add login
     *
     * @param \AppBundle\Entity\Login $login
     *
     * @return User
     */
    public function addLogin(\AppBundle\Entity\Login $login)
    {
        $this->logins[] = $login;

        return $this;
    }

    /**
     * Remove login
     *
     * @param \AppBundle\Entity\Login $login
     */
    public function removeLogin(\AppBundle\Entity\Login $login)
    {
        $this->logins->removeElement($login);
    }

    /**
     * Get logins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogins()
    {
        return $this->logins;
    }

    /**
     * Set crimeChances
     *
     * @param array $crimeChances
     *
     * @return User
     */
    public function setCrimeChances($crimeChances)
    {
        $this->crimeChances = $crimeChances;

        return $this;
    }

    /**
     * Get crimeChances
     *
     * @return array
     */
    public function getCrimeChances()
    {
        return $this->crimeChances;
    }

    /**
     * Set gtaChances
     *
     * @param array $gtaChances
     *
     * @return User
     */
    public function setGtaChances($gtaChances)
    {
        $this->gtaChances = $gtaChances;

        return $this;
    }

    /**
     * Get gtaChances
     *
     * @return array
     */
    public function getGtaChances()
    {
        return $this->gtaChances;
    }

    /**
     * Set lastCrime
     *
     * @param \DateTime $lastCrime
     *
     * @return User
     */
    public function setLastCrime($lastCrime)
    {
        $this->lastCrime = $lastCrime;

        return $this;
    }

    /**
     * Get lastCrime
     *
     * @return \DateTime
     */
    public function getLastCrime()
    {
        return $this->lastCrime;
    }

    /**
     * Set lastGta
     *
     * @param \DateTime $lastGta
     *
     * @return User
     */
    public function setLastGta($lastGta)
    {
        $this->lastGta = $lastGta;

        return $this;
    }

    /**
     * Get lastGta
     *
     * @return \DateTime
     */
    public function getLastGta()
    {
        return $this->lastGta;
    }
}
