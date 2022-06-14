<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Etudiant;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;
use App\Repository\AnneeScolaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(
        InscriptionRepository $repo, SessionInterface $session,
        PaginatorInterface $paginator,
        Request $request
        ):Response{
        //$data = $repo->findAll();
        $data = $repo->findBy(array(), array('id' => 'desc'));
        $inscriptions = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'ClasseController',
            'inscriptions'=>$inscriptions,
        ]);
    }

    #[Route('/inscription/new', name: 'app_create_inscription')]
    public function create(AnneeScolaireRepository $repoAn,InscriptionRepository $repoIns,Etudiant $etudiant = null,Inscription $insc=null,Request $request, EntityManagerInterface $manager,UserPasswordHasherInterface $encoder, EtudiantRepository $repo,SessionInterface $session)
    {
        if(!$insc){
            $insc = new Inscription();
            
        }
       
        $form = $this->createForm(InscriptionType::class,$insc);

        $form->handleRequest($request);
        //dump($request->request);

        if($form->isSubmitted() && $form->isValid()){
            $user = new User();
            $etudiant = new Etudiant();

            $email = $insc->getEtudiant()->getNomComplet();
            $email = str_replace(' ','',$email);
            $lastId = $repo->findBy(array(), array('id' => 'desc'),1,0);
            $lastId = $lastId[0]->getId();
            $email = $email.$lastId;

            $pass = 'test';
            $hashedPassword = $encoder->hashPassword($user, $pass);
	
            $user = $this->getUser();
           // dd($session->get('annee')[0]);
            $current_year = $session->get('annee')[0];
            $current_year = $current_year->getId();
            $current_year = $repoAn->find($current_year);
            $insc->getEtudiant()->setMatricule($repoIns->genererMatricule());
            $insc->getEtudiant()->setLogin($email.'@gmail.com');
            $insc->getEtudiant()->setPassword($hashedPassword);
            $insc->getEtudiant()->setRoles(["ROLE_ETUDIANT"]);
            $insc->setAc($user);
            $insc->setAnnee($current_year);

            $manager->persist($insc->getEtudiant());
            $manager->flush();

            $insc->setEtat('en cours');

            $manager->persist($insc);
            $manager->flush();
            $this->addFlash('success','l inscription a bien été  enregistré ');
            return $this->redirectToRoute('app_create_inscription');
        }
        return $this->render('inscription/create.html.twig', [
            'formInscription' => $form->createView(),
            'editMode' => $insc->getId() !== null,
            'matricule'=>''
        ]);
    }

    #[Route('/inscription/reinsc/{id}', name: 'app_create_reinscription')]
    public function reinscription(Etudiant $etudiant = null,Inscription $insc = null ,Request $request, EntityManagerInterface $manager, InscriptionRepository $repo,SessionInterface $session){
        $insc->setId(null);
        $reIns = new Inscription;
        $reIns->setEtudiant($insc->getEtudiant())
        ->setClasse($insc->getClasse())
        ->setAc($insc->getAc()); 
        $form = $this->createForm(InscriptionType::class,$reIns);
        $form->handleRequest($request);      
        $reIns->setEtat('en cours');
    
        if($form->isSubmitted() && $form->isValid()){
            $repo->add($reIns,true);
            $this->addFlash('success','la reinscription a bien été  enregistré ');
            return $this->redirectToRoute('app_inscription');
        }
        $editMode = $insc->getId();
       // dd($reIns->getEtudiant()->getMatricule());
        return $this->render('inscription/create.html.twig', [
            'formInscription' => $form->createView(),
            'editMode' => $editMode == null,
            'matricule'=>$reIns->getEtudiant()->getMatricule(),
        ]);

    }


}
