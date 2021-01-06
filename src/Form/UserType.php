<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('roles', ChoiceType::class,[
                'multiple' => true,
                'expanded' => false,
                'choices' => [
                    'Administrateur ' => 'ROLE_ADMIN',
                    'Commercial ' => 'ROLE_COMMERCIAL',
                    'Responsable des prêts' => 'ROLE_RESPONSABLE',
                    'Service Maintenance' => 'ROLE_MAINTENANCE'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
