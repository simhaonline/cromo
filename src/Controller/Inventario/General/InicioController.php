<?php

namespace App\Controller\Inventario\General;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
    /**
     * @Route("/inventario/general/inicio", name="inventario_general_general_inicio_ver")
     */
    public function lista()
    {


        return $this->render('inventario/general/inicio.html.twig'
        );
    }
}
