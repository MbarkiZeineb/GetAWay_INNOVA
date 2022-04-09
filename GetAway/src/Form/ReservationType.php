<?php

namespace App\Form;

use App\Entity\Reservation;
use http\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateReservation')
            ->add('nbrPlace')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('etat')
            ->add('type')
            ->add('idClient',EntityType::class,['class'=>\App\Entity\User::class,'choice_label'=>'nom'])
            ->add('idVoyage',EntityType::class,['class'=>\App\Entity\Voyageorganise::class,'choice_label'=>'villedest'])
            ->add('idActivite',EntityType::class,['class'=>\App\Entity\Activite::class,'choice_label'=>'nom'])
            ->add('idHebergement',EntityType::class,['class'=>\App\Entity\Hebergement::class,'choice_label'=>'adress'])
            ->add('idVol',EntityType::class,['class'=>\App\Entity\Vol::class,'choice_label'=>'villeArrivee'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
