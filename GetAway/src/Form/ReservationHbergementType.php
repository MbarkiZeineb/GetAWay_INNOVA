<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationHbergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('dateDebut',DateType::class, array(
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,))
            ->add('dateFin',DateType::class, array(
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
                'attr' => array('class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'data-format' => 'dd-mm-yyyy',
                )))
            ->add('idClient',EntityType::class,['class'=>\App\Entity\User::class,'choice_label'=>'nom'])
            ->add('Ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,'Hebergement'
        ]);
    }
}
