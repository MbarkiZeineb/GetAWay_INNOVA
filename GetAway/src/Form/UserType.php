<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('password',PasswordType::class)
            ->add('securityq',ChoiceType::class,[
                'choices' => [
                    'veuillez choisir '=> null,
                    'votre premiere voiture' => 'votre premiere voiture',
                    'ton idole' => 'ton idole',
                    'pays de tes reves' => 'pays de tes reves'

                ],
            ])
            ->add('answer')
            ->add('email')
            ->add('adresse')
            ->add('numtel')
            ->add('role',ChoiceType::class,[
                'choices' => [
                    'veuillez choisir '=> null,
                    'Client' => 'Client',
                    'Offreur' => 'Offreur'
                ],
            ])

            ->add('solde') ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
