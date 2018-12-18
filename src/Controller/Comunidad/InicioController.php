<?php

namespace App\Controller\Comunidad;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    /**
     * @Route("/comunidad/general/inicio", name="comunidad_general_inicio")
     */
    public function index()
    {
        return $this->render('comunidad/inicio.html.twig');
    }
}
