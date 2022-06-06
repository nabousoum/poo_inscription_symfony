<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Repository\ProfesseurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ProfesseurController extends AbstractController
{
    #[Route('/professeur', name: 'app_professeur')]
    public function index(
        ProfesseurRepository $repo,
        PaginatorInterface $paginator,
        Request $request
        ): Response
    {
       //$data = $repo->findAll();
       $data = $repo->findBy(array(), array('id' => 'desc'));
       $profs = $paginator->paginate(
        $data,
        $request->query->getInt('page',1),
        4
    );
        return $this->render('professeur/index.html.twig', [
            'controller_name' => 'ProfesseurController',
            'profs' => $profs
        ]);
    }

    #[Route('/professeur/new', name: 'app_create_prof')]
    #[Route('/professeur/{id}/edit', name: 'app_edit_prof')]
     public function create(Professeur $prof = null,Request $request, EntityManagerInterface $manager){
 
         if(!$prof){
             $prof = new Professeur();
         }
         
         $form = $this->createForm(ProfesseurType::class,$prof);
 
         $form->handleRequest($request);
 
         if($form->isSubmitted() && $form->isValid()){
 
             $manager->persist($prof);
             $manager->flush();
             if(!$prof->getId()){
                 $this->addFlash('success','le professeur a bien été  modifié ');
             }
             else{
                 $this->addFlash('success','le professeur a bien été  enregistré ');
             }
 
         }
 
         return  $this->render('professeur/create.html.twig',[
             'formProf' => $form->createView(),
             'editMode' => $prof->getId() !== null
         ]);
     }

     #[Route('/professeur/{id}/delete', name: 'app_delete_prof')]
     public function delete(Professeur $prof,ManagerRegistry $doctrine):Response{
         $em =  $doctrine->getManager();
         $em->remove($prof);
         $em->flush();
         return $this->redirectToRoute('app_professeur');
     }

     #[Route('/professeur/{id}/detail', name: 'app_detail_prof')]
     public function detail(Professeur $prof,ManagerRegistry $doctrine){
         $profs = $doctrine->getRepository(Professeur::class)->find($prof->getId());
         //dd($classes);
         return  $this->render('professeur/detail.html.twig',[
            'profs' => $profs
         ]);
     }
}
