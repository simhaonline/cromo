<?php

namespace App\Controller\Principal;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class InicioController extends Controller
{
   /**
    * @Route("/", name="inicio")
    */    
    public function inicio(Request $request, TokenStorageInterface $user)
    {

        $em = $this->getDoctrine()->getManager();
        return $this->render('principal/inicio.html.twig');
    }
}

