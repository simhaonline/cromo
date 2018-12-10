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
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $em=$this->getDoctrine()->getManager();
        $arPerfil=$em->getRepository('App:Social\SocPerfil')->listaPerfil($clave, $usuario);
        for($i=0;$i<count($arPerfil);$i++){
            $arPerfil[$i]['foto']="data:image/'jpeg';base64,".base64_encode(stream_get_contents($arPerfil[$i]['foto']));
        }
        return $this->render('social/buscar/busqueda.html.twig',[
            'perfil'=>$arPerfil,
        ]);
    }
}
