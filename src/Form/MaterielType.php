<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\CategoryMateriel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serialNumber')
            ->add('reference')
            ->add('description')
            ->add('status', ChoiceType::class,[
                'multiple' => false,
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Maintenance' => 'En maintenance',
                    'Pret' => 'En prêt'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => CategoryMateriel::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
