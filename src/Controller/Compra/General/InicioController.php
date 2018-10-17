<?php

namespace App\Controller\Compra\General;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
    /**
     * @Route("/compra/general/inicio", name="compra_general_general_inicio_ver")
     */
    public function lista()
    {


        return $this->render('compra/general/inicio.html.twig'
        );
    }
}
