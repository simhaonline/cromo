<?php

namespace App\Controller\Transporte\Administracion\Configuracion;


use App\Entity\Transporte\TteConfiguracion;
use App\Form\Type\Transporte\ClienteType;
use App\Form\Type\Transporte\ConfiguracionType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfiguracionController extends Controller
{

    /**
     * @Route("/transporte/administracion/configuracion/configuracion/lista/{id}", name="transporte_administracion_configuracion_configuracion_lista")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository(TteConfiguracion::class)->find(1);
        $form = $this->createForm(ConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arConfiguracion);
                $em->flush();
                Mensajes::info('Se guardo la configuracion con exito');
            }
        }
        return $this->render('transporte/administracion/configuracion/configuracion.html.twig', [
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView()
        ]);
    }

}

