<?php

namespace App\Controller\Social;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class BuscarController extends BaseController
{
    /**
     * @Route("/social/buscar/general/{clave}", name="social_buscar_general")
     */
    public function buscar(RequestStack $request, $clave)
    {
        $em=$this->getDoctrine()->getManager();
        $arPerfil=$em->getRepository('App:Social\SocPerfil')->listaPerfil();
        return $this->render('social/buscar/busqueda.html.twig',[
            'perfil'=>$arPerfil,
        ]);
    }
}
