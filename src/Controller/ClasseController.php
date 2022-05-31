<?php

namespace App\Controller;

use App\Repository\ClasseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerMibWvKx\PaginatorInterface_82dac15;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    #[Route('/classe', name: 'app_classe')]
    public function index(
        ClasseRepository $repo, SessionInterface $session,
        PaginatorInterface $paginator,
        Request $request
        ):Response{
        $data=$repo->findAll();
        $classes = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('classe/index.html.twig', [
            'controller_name' => 'ClasseController',
            'classes'=>$classes
        ]);
    }
}
