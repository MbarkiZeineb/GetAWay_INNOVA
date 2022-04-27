<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\DateTime;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('descrip')
            ->add('duree', ChoiceType::class, [
                'choices' => [
                    ''=>'',
                    '15 minutes' => '15 minutes',
                    '30 minutes' => '30 minutes',
                    '50 minutes' => '50 minutes',
                    '1 Heure' => '1 Heure',
                    '1 Heure 15 minutes' => '1 Heures 15 minute',
                    '1 Heure 30 minutes' => '1 Heures 30 minute',
                    '1 Heure 50 minutes' => '1 Heures 50 minute',
                    '2 Heures' => '2 Heures',
                    '1 Heures 15 minutes' => '1 Heures 15 minute',
                    '1 Heures 30 minutes' => '1 Heures 30 minute',
                    '2 Heures 50 minutes' => '2 Heures 50 minutes'
                    ]])
            ->add('nbrplace')
            ->add('date',DateTimeType::class, array(
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
                'attr' => array('class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datetimepicker',
                    'data-format' => 'dd-mm-yyyy HH:ii',
                ), ))
            ->add('type',  ChoiceType::class, [
                'choices' => [
                    ''=>'',
                    'Sport' => 'Sport',
                    'Educative' => 'Educative',
                    'Loisir' => 'Loisir',
                    'Aventure' => 'Aventure']])
            ->add('location')
            ->add('prix')
            ->add('imageFile', VichImageType::class)

            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
