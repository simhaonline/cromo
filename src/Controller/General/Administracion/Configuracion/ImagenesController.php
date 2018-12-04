<?php

namespace App\Controller\General\Administracion\Configuracion;

use App\Controller\BaseController;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImagenesController extends BaseController
{
    /**
     * @Route("/general/administracion/configuracion/imagenes", name="general_administracion_configuracion_imagenes")
     */
    public function lista(Request $request)
    {
        $form=$this->createFormBuilder();
            $form->add('logo',FileType::class,[]);
        $form->getForm()->handleRequest($request);

        return $this->render('transporte/movimiento/transporte/despacho/lista.html.twig', [
            'form' => $form->createView(),
        ]);
//        return $this->render('general/administracion/configuracion/imagenes.html.twig', [
//            'form' => $form->create(),
//        ]);
    }
}
