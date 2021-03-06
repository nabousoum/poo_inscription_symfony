<?php

namespace App\Controller;
use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    #[Route('/module/new', name: 'app_create_module')]
    #[Route('/module/{id}/edit', name: 'app_edit_module')]
    public function index(
        ModuleRepository $repo,
        PaginatorInterface $paginator,
        Module $module = null,
        Request $request,
         EntityManagerInterface $manager
        ): Response
    {
       // $data = $repo->findAll();
        $data = $repo->findBy(array(), array('id' => 'desc'));
        $modules = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            5
        );
        if(!$module){
            $module = new Module();
        }
        
        $form = $this->createForm(ModuleType::class,$module);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($module);
            $manager->flush();
            if(!$module->getId()){
                $this->addFlash('success','la classe a bien été  modifié ');
            }
            else{
                $this->addFlash('success','la classe a bien été  enregistré ');
            }

        }
        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'modules' => $modules,
            'formModule' => $form->createView()
        ]);
    }


    #[Route('/module/{id}/delete', name: 'app_delete_module')]
    public function delete(Module $module,ManagerRegistry $doctrine):Response{
        $em =  $doctrine->getManager();
        $em->remove($module);
        $em->flush();
        return $this->redirectToRoute('app_module');
    }

    #[Route('/module/{id}/detail', name: 'app_detail_module')]
    public function detail(Module $module,ManagerRegistry $doctrine){
        $modules = $doctrine->getRepository(Module::class)->find($module->getId());
        //dd($classes);
        return  $this->render('module/detail.html.twig',[
           'modules' => $modules
        ]);
    }
}
