<?php

namespace App\Controller\General\Administracion\Configuracion;

use App\Controller\BaseController;


use Symfony\Component\Form\Extension\Core\Type\FileType;e;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $em=$this->getDoctrine()->getManager();
        $arImagenes=$em->getRepository('App:General\GenImagen')->lista();
        for ($i=0;$i<count($arImagenes);$i++){
            $arImagenes[$i]['imagen']="data:image/'{$arImagenes[$i]['extension']}';base64,".base64_encode(stream_get_contents($arImagenes[$i]['imagen']));
        }
        $form = $this->createFormBuilder()
            ->add('logo',FileType::class,['attr'=>['class'=>'btn btn-default'],'required'=>false])
            ->add("btnGuardar", SubmitType::class, ['label' => " Guardar", 'attr' => ['class' => 'btn btn-default btn-sm']])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if ($form->get('btnGuardar')->isClicked()){
                if($form->get('logo')->getData()!=""){
                    $extension=$form->get('logo')->getViewData()->getMimeType();
                    $extension=explode('/',$extension);
                    $imagen = fopen($form->get('logo')->getData()->getRealPath(),'rb');
                    $arImagen=$em->getRepository('App:General\GenImagen')->find('LOGO');
                    if($arImagen){
                        $arImagen->setImagen(stream_get_contents($imagen))
                            ->setExtension($extension[1]);
                        $em->persist($arImagen);
                        $em->flush();
                        return $this->redirect($this->generateUrl('general_administracion_configuracion_imagenes'));
                    }

                }
            }
        }

        return $this->render('general/administracion/configuracion/imagenes.html.twig', [
                'form' => $form->createView(),
                'arImagenes'=>$arImagenes
            ]
        );
    }
}
