<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departureTime', DateTimeType::class, [
                'label' => 'Date et heure du départ',
                'widget' => 'single_text',
            ])
            ->add('startingPlace', TextType::class, ['label' => 'Adresse de départ'])
            ->add('endingPlace', TextType::class, ['label' => 'Adresse d\'arrivée'])
            ->add('unitPrice', NumberType::class, ['label' => 'Prix par place'])
            ->add('initialNumberPlaces', IntegerType::class, ['label' => 'Nombre de places disponibles'])
            ->add('tireType', ChoiceType::class, [
                "choices" => [
                    "Hiver" => Trip::WINTER_TIRE,
                    "Été" => Trip::SUMMER_TIRE
                ],
                'label' => "Type de pneu du véhicule"
            ])
            ->add('availableSpacePerPassenger', ChoiceType::class, [
                "choices" => [
                    Trip::SUITCASE => Trip::SUITCASE,
                    Trip::BACKPACK => Trip::BACKPACK,
                    Trip::SMALLBAG => Trip::SMALLBAG
                ],
                'label' => "Espace disponible par passager"
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Trip'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_trip';
    }


}
