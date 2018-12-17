<?php

namespace App\Controller\Comunidad;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    /**
     * @Route("/social/general/inicio", name="social_general_inicio")
     */
    public function index()
    {
        return $this->render('social/inicio.html.twig');
    }
}
