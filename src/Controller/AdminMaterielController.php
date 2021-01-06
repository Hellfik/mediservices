<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\CategoryMaterielRepository;
use App\Repository\MaterielRepository;
use App\Repository\PretRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class AdminMaterielController extends AbstractController
{
    /**
     * Fonction qui permet de lister tous les materiaux présents en base
     * @Route("/admin/materiel", name="admin_materiel")
     */
    public function showMateriels(MaterielRepository $repo, PaginatorInterface $paginator, Request $request, CategoryMaterielRepository $repo2, PretRepository $repo3)
    {
        $categories = $repo2->findAll();
        if(isset($_GET['category']) && $_GET['category'] != ''){
            $materiaux = $paginator->paginate(
                $repo->findByCatQuery($_GET['category']),
                $request->query->getInt('page', 1), 10
            );
            $cat = $_GET['category'];
            $search = null;
            $send = true;
        }elseif(isset($_GET['search']) && $_GET['search'] != ''){
            $materiaux = $paginator->paginate(
                $repo->findBySearchQuery($_GET['search']),
                $request->query->getInt('page', 1), 10
            );
            $search = $_GET['search'];
            $cat = null;
            $send = true;
        }else{
            $materiaux = $paginator->paginate(
                $repo->findAllQuery(),
                $request->query->getInt('page', 1), 10);
            $send = false;
            $search = null;
            $cat = null;

        }

        $allPrets = $repo3->findAll();
        $todayStamp = time();
        $threeDaysTimeStamp = 3600 * 24 * 3;

        foreach($allPrets as $pret){
            if($pret->getDateDebut()->getTimestamp() < $todayStamp ){
                foreach($pret->getMateriaux() as $mat){
                    $mat->setStatus('En prêt');
                }
            }
            if($pret->getReturnAt() != null){
                if($pret->getReturnAt()->getTimestamp() > $pret->getReturnAt()->getTimestamp() + $threeDaysTimeStamp ){
                    foreach($pret->getMateriaux() as $mat){
                        $mat->setStatus('Disponible');
                    }
                }
            }
        }
        return $this->render('admin/materiels/show.html.twig',[
            'materiaux' => $materiaux,
            'categories' => $categories,
            'send' => $send,
            'search' => $search,
            'cat' => $cat
        ]);
    }

    /**
     * Fonction qui permet l'ajout de materiel en base de données
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @Route("admin/materiel/add", name="admin_add_materiel")
     */
    public function addMateriel(Request $request, ObjectManager $manager){
        $materiel = new Materiel();

        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($materiel);
            $manager->flush();

            $this->addFlash('success', 'Materiel ajouté avec succes');
            return $this->redirectToRoute('admin_materiel');
        }

        return $this->render('admin/materiels/addMateriel.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * Permet de modifier un materiel
     *
     * @Route("admin/materiel/editMateriel/{id}", name="admin_edit_materiel")
     */
    public function editMateriel(Materiel $materiel, ObjectManager $manager, Request $request){
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($materiel);
            $manager->flush();

            $this->addFlash('success', 'Materiel modifié avec succès');
            return $this->redirectToRoute('admin_materiel');
        }
        return $this->render('admin/materiels/editMateriel.html.twig',[
            'form' => $form->createView(),
            'materiel' => $materiel
        ]);
    }

    /**
     * 
     *
     * @param ObjectManager $manager
     * @Route("admin/materiel/deleteMateriel/{id}", name="admin_delete_materiel")
     */
    public function deleteMateriel(Materiel $materiel, ObjectManager $manager){
        $manager->remove($materiel);
        $manager->flush();

        return $this->redirectToRoute('admin_materiel');
    }

    /**
     * @Route("admin/materiel/{id}", name="show_one_materiel")
     */

     public function showOneMateriel(Materiel $materiel){
        return $this->render("admin/materiels/showOne.html.twig",[
            'materiel' => $materiel
        ]);
     }

    
}
