<?php

namespace AppBundle\Form;

use AppBundle\Entity\Trip;
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
            ->add('departureTime', DateTimeType::class)
            ->add('startingPlace', TextType::class)
            ->add('endingPlace', TextType::class)
            ->add('unitPrice', NumberType::class)
            ->add('initialNumberPlaces', IntegerType::class)
            ->add('tireType', ChoiceType::class, [
                "choices" => [
                    "Hiver" => Trip::WINTER_TIRE,
                    "Été" => Trip::SUMMER_TIRE
                ]
            ])
            ->add('availableSpacePerPassenger', ChoiceType::class, [
                "choices" => [
                    Trip::SUITCASE => Trip::SUITCASE,
                    Trip::BACKPACK => Trip::BACKPACK,
                    Trip::SMALLBAG => Trip::SMALLBAG
                ]
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Trip'
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
