<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    #[Route('/classe', name: 'app_classe')]
    public function index(
        ClasseRepository $repo, SessionInterface $session,
        PaginatorInterface $paginator,
        Request $request,
        UserRepository $users
        ):Response{
        //$data = $repo->findAll();
        $data = $repo->findBy(array(), array('id' => 'desc'));
        $classes = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('classe/index.html.twig', [
            'controller_name' => 'ClasseController',
            'classes'=>$classes,
            'users' => $users->findAll()
        ]);
    }

    #[Route('/classe/new', name: 'app_create_classe')]
   #[Route('/classe/{id}/edit', name: 'app_edit_classe')]
   #[IsGranted('ROLE_RP', message: 'acces refuse')]
    public function create(Classe $classe = null,Request $request, EntityManagerInterface $manager){

        if(!$classe){
            $classe = new Classe();
        }
        
        $form = $this->createForm(ClasseType::class,$classe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($classe);
            $manager->flush();
            if(!$classe->getId()){
                $this->addFlash('success','la classe a bien été  modifié ');
            }
            else{
                $this->addFlash('success','la classe a bien été  enregistré ');
            }

        }

        return  $this->render('classe/create.html.twig',[
            'formClasse' => $form->createView(),
            'editMode' => $classe->getId() !== null
        ]);
    }

    #[Route('/classe/{id}/delete', name: 'app_delete_classe')]
    public function delete(Classe $classe,ManagerRegistry $doctrine):Response{
        $em =  $doctrine->getManager();
        $em->remove($classe);
        $em->flush();
        return $this->redirectToRoute('app_classe');
    }

    #[Route('/classe/{id}/detail', name: 'app_detail_classe')]
    public function detail(Classe $classe,ManagerRegistry $doctrine){
        $classes = $doctrine->getRepository(Classe::class)->find($classe->getId());
        //dd($classes);
        return  $this->render('classe/detail.html.twig',[
           'classes' => $classes
        ]);
    }

}
