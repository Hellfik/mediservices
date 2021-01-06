<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\UserType;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function showUsers(UserRepository $repo)
    {
       //Si l'utilisateur a entré une recherche
       if(isset($_GET['search']) && !empty($_GET['search'])){
        //Requete en fonction de la recherche de l'utilisateur
        $users = $repo->findFilter($_GET['search']);
        //Stock la recherche dans une variable
        $search = $_GET['search'];
        //Permet de savoir si une requete SQL a été envoyée
        $send = true;
        //Recherche par category de materiel
    }else{
        $users = $repo->findAll();
        $search = null;
        $send = false;
    }
    return $this->render('admin/users/show.html.twig',[
        'users' => $users,
        'search' => $search,
        'send' => $send,
        'current_menu' => 'users'
    ]);
    }


    /**
     * Undocumented function
     *@Route("/admin/users/add", name="security_registration")
     * @return void
     */
    public function addUser(Request $request, ObjectManager $manager, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        $errors = $validator->validate($user);
        $errorsString = (string) $errors;


        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été créé');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users/addUser.html.twig',[
            'form'=> $form->createView(),
            'errors' => $errorsString
        ]);
    }

    /**
     * Fonction permettant de modifier l'utilisateur
     *
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     * @Route("admin/users/edit/{id}", name="admin_edit_user")
     */
    public function editUser(User $user, Request $request, ObjectManager $manager){
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setUpdatedAt(new \DateTime);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users/editUser.html.twig',[
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Fonction permettant de supprimer un utilisateur de la base de données
     *
     * @param User $user
     * @param ObjectManager $manager
     * @Route("admin/users/delete/{id}", name="admin_delete_user")
     */
    public function deleteUser(User $user, ObjectManager $manager){
        $manager->remove($user);
        $manager->flush();
        
        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('admin_users');
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param ObjectManager $manager
     * @Route("admin/selfDelete/{username}", name="admin_self_delete")
     */
    public function selfDelete(User $user, ObjectManager $manager){
        $currentUserUsername = $this->getUser()->getUsername();
        if ($currentUserUsername == $user->getUsername())
        {
            $session = $this->get('session');
            $session->invalidate();
            $session = new Session();
            $manager->remove($user);
            $manager->flush();
            $flashMessage = 'Votre compte a bien été supprimé';
            return $this->redirectToRoute('security_login',[
                'flashMessage' => $flashMessage
            ]);
        }

    }

    /**
     * Undocumented function
     *
     * @Route("/admin/user/{username}", name="admin_user_profile")
     */
    public function userProfile(User $user){
        $user = $this->getUser();
        return $this->render('admin/users/profile.html.twig');
    }
}
