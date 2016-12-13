<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    private $openMafia;

    public function __construct(array $openMafia)
    {
        $this->openMafia = $openMafia;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('money', array($this, 'moneyFilter')),
            new \Twig_SimpleFilter('rank', array($this, 'rankFilter')),
            new \Twig_SimpleFilter('location', array($this, 'locationFilter')),
        );
    }

    public function moneyFilter($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',')
    {
        $money = number_format($number, $decimals, $decPoint, $thousandsSep);
        $money = $this->openMafia['currency']['before'].$money.$this->openMafia['currency']['after'];

        return $money;
    }

    public function rankFilter($rank)
    {
        return $this->openMafia['ranks'][$rank]['name'];
    }

    public function locationFilter($location)
    {
        return $this->openMafia['locations'][$location]['name'];
    }

    public function getName()
    {
        return 'app_extension';
    }
}
