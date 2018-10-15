<?php

namespace App\Controller\Financiero\General;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
    /**
     * @Route("/financiero/general/inicio", name="financiero_general_general_inicio_ver")
     */
    public function lista()
    {


        return $this->render('financiero/general/inicio.html.twig'
        );
    }
}
