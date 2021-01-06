<?php

namespace App\Controller;

use App\Entity\Pret;
use App\Form\EditDateType;
use App\Form\EditPretType;
use App\Form\PretType;
use App\Form\RemarquesPretType;
use App\Repository\PretRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPretController extends AbstractController
{
    /**
     * @Route("/admin/pret", name="admin_pret")
     */
    public function showPrets(PretRepository $repo)
    {
        $prets = $repo->findByEndDate();
        return $this->render('admin/prets/show.html.twig',[
            'prets' => $prets,
        
        ]);
    }

    /**
     * @Route("admin/pret/editpret/{id}", name="admin_edit_pret")
     */
    public function editPret(Pret $pret, ObjectManager $manager, Request $request){
        $todayStamp = time();
        $today = date('Y-m-d', $todayStamp);

        $form = $this->createForm(EditPretType::class, $pret);
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){
            if(\date_timestamp_get($pret->getDateDebut()) >= \date_timestamp_get($pret->getDateFin())){
                $this->addFlash('danger', 'La date de fin du pret est inférieur ou egale à celle du debut, veuillez choisir des dates correctes');
            }elseif(!$pret->isBookableDates()){
                $this->addFlash(
                    'warning', 
                    'Les dates que vous avec choisies ne peuvent etre réservées : elles sont déja prises.'
                );
            }
            else{

                $pret->setUpdatedAt(new \DateTime);
                $manager->persist($pret);
                $manager->flush();
                $this->addFlash('success', 'Pret modifié avec succès');
                return $this->redirectToRoute('admin_show_one_pret', ['id' => $pret->getId()]);
            }
        }

        return $this->render('admin/prets/editPret.html.twig',[
            'form' => $form->createView(),
            'pret' => $pret,
            'today' => $today
        ]);
    }

    
    /**
     * @Route("admin/pret/cancelPret/{id}", name="admin_cancel_pret")
     */

    public function cancelPret(Pret $pret, ObjectManager $manager){
        $pret->setpretStatus('Pret annulé');
        $pret->setRemarques('Pret annulé');

        foreach($pret->getMateriaux() as $mat){
            $mat->setStatus('Disponible');
        }
        
        
        $manager->persist($pret);
        $manager->flush();

        $this->addFlash('success', 'Pret annulé avec succès');
        return $this->redirectToRoute('admin_historique_pret');
    }
    
    
    /**
     * @Route("/admin/pret/add", name="admin_add_pret")
     */
    
    public function addPret(Request $request, ObjectManager $manager){
        $pret = new Pret();

        $form = $this->createForm(PretType::class, $pret);
        $form->handleRequest($request);

        $todayStamp = time();
        $today = date('Y-m-d', $todayStamp);
        $minDateStamp = $todayStamp + 86400;
        $minDateFin = date('Y-m-d', $minDateStamp);
        
        if($form->isSubmitted() && $form->isValid()){
            if(!$pret->isBookableDates()){
                $this->addFlash(
                    'warning', 
                    'Les dates que vous avec choisies ne peuvent etre réservées : elles sont déja prises.'
                );
            }


            elseif(\date_timestamp_get($pret->getDateDebut()) >= \date_timestamp_get($pret->getDateFin())){
                $this->addFlash('danger', 'La date de fin du pret est inférieur ou egale à celle du debut, veuillez choisir des dates correctes');
            }

            
            
            else{
                $pret->setpretStatus('Pret en cours');
                $pret->setCommercial($this->getUser());
                $manager->persist($pret);
                $manager->flush();

                $this->addFlash('success', 'Pret ajouté avec succès');
                return $this->redirectToRoute('admin_prets_mesPrets');
            }
        }
        
        return $this->render('admin/prets/addPret.html.twig',[
            'form' => $form->createView(),
            'today'=> $today,
            'minDateFin' => $minDateFin
        ]);
     }
     
     /**
      *
      * @Route("admin/pret/historiquePret", name="admin_historique_pret")
      */
      public function historiquePret(PretRepository $repo){
        $prets = $repo->findByStatus();
        
        return $this->render('admin/prets/historiquePret.html.twig',[
            'prets' => $prets
        ]);
     }
     
     
     /**
      * Undocumented function
      *
      * @param Pret $pret
      * @Route("/admin/pret/{id}" , name="admin_show_one_pret")
      */
         public function showPret(Pret $pret, Request $request, ObjectManager $manager){
             //Stock le nombre de second ecoulé depuis 1970-01-01 00h00m00s
             $timeout = time();

             //Recupere le timestamp de la date de fin du pret
             $EndTimeStamps = date_timestamp_get($pret->getDateFin());
             //Calcul la différence entre le timestamp actuel et celui de la date de fin du pret
             $timeStampLeft = $EndTimeStamps - $timeout;
             

             //Convertion du timestamp en jour
             $daysLeft = $timeStampLeft / (24 * 3600);
             
             $troncDays = preg_split("/[.]/", $daysLeft);
             $dayInt = $troncDays[0];
             $hoursLeft = '0.' . $troncDays[1];

            //Convertion du timestamp restant en heure minute
             $hoursTimeStamp = $hoursLeft * 24 * 3600;
             $hours = $hoursTimeStamp / 3600;
             $minute = ($hours - preg_split("/[.]/", $hours)[0]) * 60;
             
             $timeLeft = preg_split("/[.]/" , $hours)[0] . 'h et ' . preg_split("/[.]/" , $minute)[0] . 'min';

             $formEnd = $this->createForm(RemarquesPretType::class, $pret);
             $formEnd->handleRequest($request);
             

            $lits = [];
            $fauteuils = [];
            $defibrilateurs = [];

            foreach($pret->getMateriaux() as $mat){
                if($mat->getCategory()->getName() == 'Lit médicalisé'){
                    $lits[] = $mat->getSerialNumber();
                }elseif($mat->getCategory()->getName() == 'Fauteuil roulant'){
                    $fauteuils[] = $mat->getSerialNumber();
                }else{
                    $defibrilateurs[] = $mat->getSerialNumber();
                }
            }

             if($formEnd->isSubmitted() && $formEnd->isValid()){
                 //Apres la fin d'un pret, le status d'un materiel devient 'En maintenance'
                 
                 foreach($pret->getMateriaux() as $mat){
                     $mat->setStatus('En maintenance');
                 }
                 $pret->setpretStatus('Pret finalisé');
                 //Mets à jour la date de retour du materiel prété
                 $pret->setReturnAt(new \DateTime());
                 $manager->persist($pret);
                 $manager->flush();
                 $this->addFlash('success', 'Pret finalisé avec succès');
                 return $this->redirectToRoute('admin_historique_pret');
             }
             
             return $this->render('admin/prets/showOnePret.html.twig',[
                 'pret' => $pret,
                 'formEnd' => $formEnd->createView(),
                 'dayLeft' => $dayInt,
                 'timeLeft' => $timeLeft,
                 'lits' => $lits,
                 'fauteuils' => $fauteuils,
                 'defibrilateurs' => $defibrilateurs
             ]);
         }

         /**
          * Fonction permettant de montrer les prets en fonction de l'utilisateur connecté
          * Seuls les prets concernant l'utilisateur connecté peuvent etre mofifiés par celui-ci
          *
          *@Route("admin/prets/mesPrets", name="admin_prets_mesPrets")
          * @return void
          */
         public function myLoans(PretRepository $repo){
            $connectedUserId = $this->getUser()->getId();
            $myLoans = $repo->getPretByUser($connectedUserId);

            return $this->render('admin/prets/mesPrets.html.twig', [
                'mesPrets' => $myLoans
            ]);
         }

         /**
          * Affiche un le pret du commercial connecté
          *
          * @param Pret $pret
          * @Route("admin/prets/mesPrets/{id}", name="admin_pret_monPret")
          */
         public function myLoan(Pret $pret){

             //Stock le nombre de second ecoulé depuis 1970-01-01 00h00m00s
             $timeout = time();

             //Recupere le timestamp de la date de fin du pret
             $EndTimeStamps = date_timestamp_get($pret->getDateFin());
             //Calcul la différence entre le timestamp actuel et celui de la date de fin du pret
             $timeStampLeft = $EndTimeStamps - $timeout;
             

             //Convertion du timestamp en jour
             $daysLeft = $timeStampLeft / (24 * 3600);
             
             $troncDays = preg_split("/[.]/", $daysLeft);
             $dayInt = $troncDays[0];
             $hoursLeft = '0.' . $troncDays[1];

            //Convertion du timestamp restant en heure minute
             $hoursTimeStamp = $hoursLeft * 24 * 3600;
             $hours = $hoursTimeStamp / 3600;
             $minute = ($hours - preg_split("/[.]/", $hours)[0]) * 60;
             
             $timeLeft = preg_split("/[.]/" , $hours)[0] . 'h et ' . preg_split("/[.]/" , $minute)[0] . 'min';

            $lits = [];
            $fauteuils = [];
            $defibrilateurs = [];

            foreach($pret->getMateriaux() as $mat){
                if($mat->getCategory()->getName() == 'Lit médicalisé'){
                    $lits[] = $mat->getSerialNumber();
                }elseif($mat->getCategory()->getName() == 'Fauteuil roulant'){
                    $fauteuils[] = $mat->getSerialNumber();
                }else{
                    $defibrilateurs[] = $mat->getSerialNumber();
                }
            }
            return $this->render('admin/prets/monPret.html.twig',[
                'pret' => $pret,
                'lits' => $lits,
                'fauteuils' => $fauteuils,
                'defibrilateurs' => $defibrilateurs,
                'dayLeft' => $dayInt,
                'timeLeft' => $timeLeft,
                
            ]);
         }

         /**
          * Fonction permettant de mettre à jour la date d'envoie des différents materiaux concernant un pret en particulier
          *
          * @param Pret $pret
          * @param Request $request
          * @param ObjectManager $manager
          * @Route("admin/pret/{id}/editSendDates", name="admin_pret_sendDates")
          * @return Reponse
          */
         public function editSendAt(Pret $pret, Request $request, ObjectManager $manager){
            $todayStamp = time();
            $today = date('Y-m-d', $todayStamp);
            
            $form = $this->createForm(EditDateType::class, $pret);
            $form->handleRequest($request);

            $dateMin =  date('Y-m-d',date_timestamp_get($pret->getDateDebut()));
            $dateMax =  date('Y-m-d',date_timestamp_get($pret->getDateFin()));
            
            
            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($pret);
                $manager->flush();

                $this->addFlash('success', 'Date d\'envoie bien modifiée');
                return $this->redirectToRoute('admin_show_one_pret', ['id' => $pret->getId()]);
            }

            return $this->render('admin/prets/editSendAt.html.twig', [
                'pret' => $pret,
                'form' => $form->createView(),
                'today' => $today,
                'dateMin' => $dateMin,
                'dateMax' => $dateMax
            ]);
         }
}
