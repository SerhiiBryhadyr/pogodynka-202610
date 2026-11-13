<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ExternalAPIType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'scale' => 7,
                'html5' => true,
                'constraints' => [
                    new NotBlank(),
                    new Range(min: -90, max: 90),
                ],
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'scale' => 7,
                'html5' => true,
                'constraints' => [
                    new NotBlank(),
                    new Range(min: -180, max: 180),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
