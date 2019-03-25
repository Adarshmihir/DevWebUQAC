<?php

namespace App\Form;

use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Type', ChoiceType::class, [
                'choices'  => [
                    'Passager' => User::PASSENGER_TYPE,
                    'Conducteur' => User::DRIVER_TYPE,
                    'Les deux' => User::BOTH_TYPE,
                ]
            ])
            ->add('AutoriseLaCigarette', CheckboxType::class, [
                'required' => false
            ])
            ->add('AccesAMonNumeroDeTelephone', CheckboxType::class, [
                'required' => false
            ])
            ->add('AccesAMonAdresseMail', CheckboxType::class, [
                'required' => false
            ])
            ->add('AirConditionne', CheckboxType::class, [
                'required' => false
            ])
            ->add('AnimauxAutorises', ChoiceType::class, [
                'choices'  => [
                    'Non' => User::NO_ANIMALS,
                    'En cage' => User::ANIMALS_IN_CAGE,
                    'Oui' => User::FREE_ANIMALS,
                    'Indifférent' => User::INDIFFERENT_ANIMALS,
                ],
                'required' => true
            ])
            ->add('SupportAVelo', CheckboxType::class, [
                'required' => false
            ])
            ->add('SupportASki', CheckboxType::class, [
                'required' => false
            ])
            ->add('NumeroDeTelephone', null, [
                'required' => false
            ])
        ;
    }
}
