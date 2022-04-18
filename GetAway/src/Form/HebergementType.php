<?php

namespace App\Form;

use App\Entity\Hebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('description')
            ->add('photo')
            ->add('dateStart')
            ->add('dateEnd')
            ->add('contact')
            ->add('nbrDetoile')
            ->add('nbrSuite')
            ->add('nbrParking')
            ->add('modelCaravane')
            ->add('offreur')
            ->add('idCateg')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
        ]);
    }
}
