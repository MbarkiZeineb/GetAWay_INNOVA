<?php

namespace App\Form;

use App\Entity\Avion;
use App\Entity\Vol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numVol')
            ->add('dateDepart',DateTimeType::class, array(
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
                'attr' => array('class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'data-format' => 'dd-mm-yyyy HH:ii',
                ), ))
            ->add('dateArrivee',DateTimeType::class, array(
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
                'attr' => array('class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'data-format' => 'dd-mm-yyyy HH:ii',
                ), ))
            ->add('villeDepart', ChoiceType::class,array(
                'choices' => array(''=>'',
                    'Tunis-Carthage' => 'Tunis-Carthage',
                    'Tozeur-Nafta' => 'Tozeur-Nafta',
                    'Monastir'   => 'Monastir',
                    'Enfidha-Hammamet'   => 'Enfidha-Hammamet'
                )))
            ->add('villeArrivee',ChoiceType::class,array(
                'choices' => array(''=>'',
                    'Paris-CharlesGaulle' => 'Paris-CharlesGaulle',
                    'Pierre-Elliott-Trudeau-Montréal' => 'Pierre-Elliott-Trudeau-Montréal',
                    'Hambourg-Allemagne'   => 'Hambourg-Allemagne',
                    'Doha-International'   => 'Doha-International',
                    'NgurahRai-Indonisie'   => 'NgurahRai-Indonisie',
                    'Dubai-International'   => 'Dubai-International',
                )))
            ->add('nbrPlacedispo')
            ->add('idAvion' , EntityType::class,array(
                'class' => Avion::class,
                'choice_label' => function($avion) {
                    /** @var Avion $avion */
                    return $avion->getNomAvion() ;
                }
            ))
            ->add('prix')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vol::class,

        ]);
    }
}