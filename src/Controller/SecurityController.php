<?php

namespace App\Controller;

use App\Entity\AnneeScolaire;
use App\Repository\UserRepository;
use App\Repository\AnneeScolaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,SessionInterface $session,AnneeScolaireRepository $RepoAn): Response
    {
        $annee = $RepoAn->findBy(array('etat'=>'en cours'));
        //$annee = $annee;
        $session->set('annee',$annee);
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
       // dd($session->get('annee'));
       //dd($session);
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/user', name: 'app_user')]
    public function userList(UserRepository $repo)
    {
        return $this->render('nav.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }
}
