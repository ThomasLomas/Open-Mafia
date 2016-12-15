<?php

namespace AppBundle\Model;

class Crime
{
    private $crime = 0;
    private $chance = 0;

    /**
     * Gets the value of crime.
     *
     * @return mixed
     */
    public function getCrime()
    {
        return $this->crime;
    }

    /**
     * Sets the value of crime.
     *
     * @param mixed $crime the crime
     *
     * @return self
     */
    public function setCrime($crime)
    {
        $this->crime = $crime;

        return $this;
    }

    /**
     * Gets the value of chance.
     *
     * @return mixed
     */
    public function getChance()
    {
        return $this->chance;
    }

    /**
     * Sets the value of chance.
     *
     * @param mixed $chance the chance
     *
     * @return self
     */
    public function setChance($chance)
    {
        $this->chance = $chance;

        return $this;
    }
}
