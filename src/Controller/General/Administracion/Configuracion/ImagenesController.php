<?php

namespace App\Controller\General\Administracion\Configuracion;

use App\Controller\BaseController;

use App\Entity\General\GenImagen;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ImagenesController extends BaseController
{
//    protected $clase= GenImagen::class;
//    protected $claseNombre = "GenImagenes";
//    protected $modulo = "General";
//    protected $funcion = "Configuracion";
//    protected $grupo = "General";
//    protected $nombre = "Imagenes";
    /**
     * @Route("/general/administracion/configuracion/imagenes", name="general_administracion_configuracion_imagenes")
     */
    public function lista(Request $request)
    {

        return $this->render('general/administracion/configuracion/imagenes.html.twig', [
            'form' => $this->createFormBuilder()
        ->add('logo',FileType::class,[])
        ->getForm()->handleRequest($request)->createView(),
        ]
        );
//        return $this->render('general/administracion/configuracion/imagenes.html.twig', [
//            'form' => $form->create(),
//        ]);
    }
}
