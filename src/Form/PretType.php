<?php

namespace App\Form;

use App\Entity\Pret;
use App\Entity\Client;
use App\Entity\Materiel;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class PretType extends AbstractType
{
    private $em;
    
    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('client', EntityType::class,[
                'class' => Client::class,
                'choice_label' => 'displayName'
                
            ])
            ->add('dateDebut', DateType::class,[
                'widget' => 'single_text',
                'attr'   => ['class' => 'js-datepicker form-control', 'autocomplete' =>'off' ]

            ])
            ->add('dateFin',DateType::class,[
                'widget' => 'single_text',
                'attr'   => ['class' => 'js-datepicker form-control', 'autocomplete' =>'off']

            ])
            ->add('remarques', TextareaType::class,[
                'required' => false
            ])
            
            ->add('materiaux',  EntityType::class, [
                    'class' => Materiel::class,
                    // 'query_builder' => function(MaterielRepository $repo){
                    //     return $repo->createQueryBuilder('m')
                    //     ->andWhere('m.status = :status1 or m.status = :status2')
                    //     ->setParameters([
                    //         'status1' => 'Disponible',
                    //         'status2' => 'En prêt'
                    //     ]);
                        

                    // ;
                        
                    // },
                    'choice_label' => 'serialNumber',
                    'multiple' => true,
                    //Regroupe les resultats en fonction de la category du materiel
                    'group_by' => function(Materiel $materiel){
                        return $materiel->getCategory()->getName();
                    }
                    ]);
            
        // $builder
        // ->add('materiels',  CollectionType::class, [
        //         'entry_type' => MaterielType::class,
        //         'entry_options' => ['label' => false],
        //     ]);

            // ->add('category', EntityType::class, [
            //     'class' => 'App\Entity\CategoryMateriel',
            //     'choice_label' => 'name',
            //     'mapped' => false,
            // ]);
            
                


                // $builder->addEventListener(
                //     FormEvents::PRE_SET_DATA,
                //     function(FormEvent $event)
                //     {
                //         $form = $event->getForm();
                //         $data = $event->getData();

                //         $materiel = $data->getMateriel();
                //         dump($materiel);
                //         if($materiel){
                //             $form->get('category')->setData($materiel->getCategory());

                //             $form->add('materiel', EntityType::class,[
                //                 'class' => Materiel::class,
                //                 'placeholder' => 'Selectionne un materiel',
                //                 'choices' => $materiel->getCategory()->getSerialNumber()
                //             ]);
                //         }else
                //         {
                //             $form->add('materiel', EntityType::class,[
                //                 'class' => Materiel::class,
                //                 'placeholder' => 'Selectionne un materiel',
                //                 'choices' => []
                //             ]);
                //         }
                //     }
                // );

            

       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pret::class,
        ]);
    }
}
