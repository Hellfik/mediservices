<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="security_login")
     */

    public function login(AuthenticationUtils $authenticationUtils){
        // Recupere les erreurs de connexion, s'il y en a
        $error = $authenticationUtils->getLastAuthenticationError();
        // Le dernier username entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig',[
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */

    public function logout(){}
}
