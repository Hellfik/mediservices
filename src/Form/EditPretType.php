<?php

namespace App\Form;

use App\Entity\Pret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EditPretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('dateDebut', DateType::class,[
            'widget' => 'single_text',
            'attr'   => ['class' => 'js-datepicker form-control', 'autocomplete' =>'off' ]

        ])
        ->add('dateFin',DateType::class,[
            'widget' => 'single_text',
            'attr'   => ['class' => 'js-datepicker form-control', 'autocomplete' =>'off']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pret::class,
        ]);
    }
}
