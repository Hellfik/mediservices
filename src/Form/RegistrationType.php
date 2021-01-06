<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'required' => false,
            ])
            ->add('email', TextType::class,[
                'required' => false,
            ])
            ->add('password', PasswordType::class,[
                 'required' => false,
            ])
            ->add('confirm_password',PasswordType::class,[
                'required' => false,
            ])
            ->add('roles', ChoiceType::class,[
                'multiple' => true,
                'expanded' => true,
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
