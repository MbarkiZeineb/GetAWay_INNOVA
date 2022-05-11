<?php

namespace App\Form;

use App\Entity\Reservation;
use phpDocumentor\Reflection\PseudoTypes\IntegerRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nbrPlace', TextType::class,[
                'label' => $this->app->translator->translate('forms.MyPaymentsProductsType.labels.price'),
                'html5' => true,
            ])
            ->add('submit',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,'validation_groups' => ['Default', 'cart']
        ]);
    }
}
