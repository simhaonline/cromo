<?php

namespace App\Controller\Contabilidad\General;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
    /**
     * @Route("/contabilidad/general/inicio", name="contabilidad_general_general_inicio_ver")
     */
    public function lista()
    {


        return $this->render('contabilidad/general/inicio.html.twig'
        );
    }
}
