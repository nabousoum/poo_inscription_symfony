<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use App\Entity\Inscription;
use App\Repository\DemandeRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class DemandeController extends AbstractController
{
    #[Route('/demande', name: 'app_demande')]
    public function index(
        DemandeRepository $repo, SessionInterface $session,
        PaginatorInterface $paginator,
        Request $request
        ):Response{
        //$data = $repo->findAll();
        $data = $repo->findBy(array('etat'=>'en cours'), array('id' => 'desc'));
        $dataV = $repo->findBy(array('etat'=>'valide'), array('id' => 'desc'));

        $demandes = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('demande/index.html.twig', [
            'controller_name' => 'DemandeController',
            'demandes' => $demandes,
            'demandesV'=>$dataV
        ]);
    }

    #[Route('/demande/etu', name: 'app_demande_etu')]
    #[IsGranted('ROLE_ETUDIANT', message: 'acces refuse')]
    public function demandeEtu(
        DemandeRepository $repo, SessionInterface $session,
        PaginatorInterface $paginator,
        Request $request,
        InscriptionRepository $repoEtu
        ):Response{
        //$data = $repo->findAll();
        $user = $this->getUser();

        $idEtu = $user->getId();
        $idInsc = $repoEtu->findOneBy(array('etudiant' => $idEtu));
        $data = $repo->findBy(array('inscription'=>$idInsc), array('id' => 'desc'));

        $demandes = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('demande/listeEtu.html.twig', [
            'controller_name' => 'DemandeController',
            'demandes' => $demandes
        ]);
    }


    #[Route('/demande/new', name: 'app_create_demande')]
    public function create(InscriptionRepository $repo,Demande $demande = null,Request $request, EntityManagerInterface $manager){

        if(!$demande){
            $demande = new Demande();
        }
        
        $form = $this->createForm(DemandeType::class,$demande);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getUser();

            $idEtu = $user->getId();
            $idInsc = $repo->findOneBy(array('etudiant' => $idEtu));
            //dd($idInsc);
            $demande->setEtat('en cours');
            $demande->setInscription($idInsc);
            $manager->persist($demande);
            $manager->flush();
            $this->addFlash('success','la demande a bien été  enregistré ');
        }
        return  $this->render('demande/create.html.twig',[
            'formDemande' => $form->createView(),
            'editMode' => $demande->getId() !== null
        ]);
    }

    #[Route('/demande/{id}', name: 'app_demande_valider')]

    public function validerDemande(InscriptionRepository $repoInsc,DemandeRepository $repo,Request $request, EntityManagerInterface $manager){

        $id = $request->get('id');

        $demande = $repo->find($id);
        $insc = $demande->getInscription();
        $inscription = $repoInsc->find($insc);

        $demande->setEtat('valide');
        $inscription->setEtat('valide');

        $manager->persist($demande);
        $manager->flush();
        return $this->redirectToRoute('app_demande');
    }

    #[Route('/demande/{id}/annule', name: 'app_demande_annuler')]

    public function annulerDemande(DemandeRepository $repo,Request $request, EntityManagerInterface $manager){

        $id = $request->get('id');
        $demande = $repo->find($id);
        $demande->setEtat('annule');
        $manager->persist($demande);
        $manager->flush();
        return $this->redirectToRoute('app_demande');
    }


}
