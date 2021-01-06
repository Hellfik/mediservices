<?php

namespace App\Controller;

use App\Entity\CategoryMateriel;
use App\Form\CategoryType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function showCategory()
    {
        return $this->render('admin/category/show.html.twig');
    }

    /**
     * Fonction d'ajout d'une categorie
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @Route("/admin/category/add", name="admin_add_category")
     */
    public function addCategory(Request $request, ObjectManager $manager){
        $category = new CategoryMateriel();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/addCategory.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
