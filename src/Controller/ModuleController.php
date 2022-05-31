<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(
        ModuleRepository $repo,
        PaginatorInterface $paginator,
        Request $request
        ): Response
    {
        $data = $repo->findAll();
        $modules = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'modules' => $modules
        ]);
    }
}
