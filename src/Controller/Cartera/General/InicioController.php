<?php

namespace App\Controller\Cartera\General;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends AbstractController
{
   /**
    * @Route("/cartera/general/inicio", name="cartera_general_general_inicio_ver")
    */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('base.html.twig');
    }
}

