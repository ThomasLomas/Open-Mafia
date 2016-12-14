<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CrimeType extends AbstractType
{
    private $crimeChoices = array();
    private $tokenStorage;

    public function __construct(array $openMafia, TokenStorage $tokenStorage)
    {
        $crimeChances = $tokenStorage->getToken()->getUser()->getCrimeChances();

        foreach($openMafia['crimes'] as $index => $crime) {
            $crimeChance = (isset($crimeChances[$index])) ? $crimeChances[$index] : 0;
            $crimeName = $crime['name'] . ' (' . $crimeChance . '%)';

            $this->crimeChoices[$crimeName] = $index;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->crimeChoices,
            'expanded' => true,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
