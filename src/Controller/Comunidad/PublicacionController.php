<?php

namespace App\Controller\Comunidad;

use App\Controller\Estructura\FuncionesController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublicacionController extends AbstractController
{

    public function misPublicaciones($usuario)
    {
        $misPublicaciones=FuncionesController::solicitudesGet(ApiComunidad::getApi('misPublicaciones').$usuario);
//        dump($misPublicaciones);
        if($misPublicaciones['estado']){
            return $misPublicaciones;
        }
    }
}
