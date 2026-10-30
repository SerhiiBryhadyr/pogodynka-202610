<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => fn (Location $location) => $location->getCity() . ', ' . $location->getCountry(),
                'placeholder' => 'Choose a location',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('celsius', NumberType::class, [
                'label' => 'Temperature (°C)',
                'scale' => 0,
                'html5' => true,
                'input' => 'string',
            ])
            ->add('humidity', IntegerType::class, [
                'label' => 'Humidity (%)',
            ])
            ->add('pressure', IntegerType::class, [
                'label' => 'Pressure (hPa)',
            ])
            ->add('cloudiness', IntegerType::class, [
                'label' => 'Cloudiness (%)',
            ])
            ->add('precipitation', NumberType::class, [
                'label' => 'Precipitation (mm)',
                'scale' => 1,
                'html5' => true,
                'input' => 'string',
            ])
            ->add('windSpeed', NumberType::class, [
                'scale' => 1,
                'html5' => true,
                'input' => 'string',
            ])
            ->add('windDirection', IntegerType::class, [
                'label' => 'Wind direction (°)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}