<?php

namespace App\Controller\Seguridad;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SeguridadController extends Controller
{
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        return $this->redirect($this->generateUrl('inicio'));       
    } 
}

