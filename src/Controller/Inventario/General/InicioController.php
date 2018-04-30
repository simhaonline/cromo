<?php

namespace App\Controller\Inventario\General;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
    /**
     * @Route("/inv/general/inicio", name="inv_general_inicio")
     */
    public function lista()
    {


        return $this->render('inventario/general/inicio.html.twig'
        );
    }
}
