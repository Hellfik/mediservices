<?php

namespace App\Controller;

use App\Repository\MaterielRepository;
use App\Repository\PretRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $repo, MaterielRepository $repo2, PretRepository $repo3)
    {
        $nbUsers = $repo->numberOfUsers();
        $nbMat = $repo2->numberOfMateriel();
        $nbMatAvailable = $repo2->numberOfMaterielAvailable();
        $nbMatRepair = $repo2->numberOfMaterielRepair();
        $nbPret = $repo3->numberOfLoan();
        $nbPretTotal = $repo3->totalNumberOfLoan();
        
        return $this->render('admin/index.html.twig',[
            'users' => $nbUsers,
            'materiels' => $nbMat,
            'matAvailable' => $nbMatAvailable,
            'matMaintenance' => $nbMatRepair,
            'nbPret' => $nbPret,
            'totalNumberOfLoan' => $nbPretTotal,
            'current_menu' => 'home'
        ]);
    }

    
}
