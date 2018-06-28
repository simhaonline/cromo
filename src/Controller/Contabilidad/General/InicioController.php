<?php

namespace App\Controller\Contabilidad\General;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
    /**
     * @Route("/ctb/general/inicio", name="contabilidad_general_inicio")
     */
    public function lista()
    {


        return $this->render('contabilidad/general/inicio.html.twig'
        );
    }
}
