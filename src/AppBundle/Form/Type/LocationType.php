<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LocationType extends AbstractType
{
    private $locationChoices = array();

    public function __construct(array $openMafia)
    {
        foreach($openMafia['locations'] as $index => $location) {
            $this->locationChoices[$location['name']] = $index;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->locationChoices
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
