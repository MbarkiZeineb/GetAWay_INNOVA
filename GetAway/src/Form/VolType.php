<?php

namespace App\Form;

use App\Entity\Avion;
use App\Entity\Vol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class VolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('villeDepart')
            ->add('villeArrivee')
            ->add('nbrPlacedispo')
            ->add('idAvion',EntityType::class,[
                'class'=> Avion::class,
                'choice_label'=>'nomAvion'])
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
