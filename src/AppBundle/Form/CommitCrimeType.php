<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\Form\Type\CrimeType;

class CommitCrimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('crime', CrimeType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('submit', SubmitType::class, ['label' => 'Commit Crime'])
        ;
    }
}