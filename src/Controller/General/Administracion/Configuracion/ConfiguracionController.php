<?php

namespace App\Controller\General\Administracion\Configuracion;


use App\Entity\General\GenConfiguracion;

use App\Entity\General\GenLicencia;
use App\Form\Type\General\ConfiguracionType;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfiguracionController extends Controller
{

    /**
     * @Route("/general/administracion/configuracion/configuracion/lista/{id}", name="general_administracion_configuracion_configuracion_lista")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find($id);
            $form = $this->createForm(ConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {

//                //datos estaticos para licencia (prueba)
//                if($em->getRepository('App:General\GenLicencia')->find($form->get('codigoLicencia')->getData())){
//
//                $arLicencia=(new GenLicencia())
//                    ->setCodigoLicenciaPk($form->get('codigoLicenciaFk')->getData())
//                    ->setFechaActivacion(new \DateTime('now'))
//                    ->setFechaValidaHasta(new \DateTime('2018-12-31'))
//                    ->setCartera(1)
//                    ->setCompra(1);
//
//                //fin prueba
//                $em->persist($arLicencia);
//                }
                $em->persist($arConfiguracion);
                $em->flush();
                Mensajes::info('Se guardo la configuracion con exito');
            }
        }
        return $this->render('general/administracion/configuracion/configuracion.html.twig', [
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView()
        ]);
    }

}

