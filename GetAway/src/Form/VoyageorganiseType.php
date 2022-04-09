<?php

namespace App\Form;

use App\Entity\Voyageorganise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoyageorganiseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('villedepart')
            ->add('villedest')
            ->add('datedepart')
            ->add('datearrive')
            ->add('nbrplace')
            ->add('idcat')
            ->add('prix')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyageorganise::class,
        ]);
    }
}
