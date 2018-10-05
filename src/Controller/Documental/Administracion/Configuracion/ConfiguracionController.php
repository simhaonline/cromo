<?php

namespace App\Controller\Documental\Administracion\Configuracion;


use App\Entity\Documental\DocConfiguracion;
use App\Form\Type\Documental\ConfiguracionType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfiguracionController extends Controller
{

    /**
     * @Route("/documental/administracion/configuracion/configuracion/lista/{id}", name="documental_administracion_configuracion_configuracion_lista")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository(DocConfiguracion::class)->find(1);
        $form = $this->createForm(ConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arConfiguracion);
                $em->flush();
                Mensajes::info('Se guardo la configuracion con exito');
            }
        }
        return $this->render('documental/administracion/configuracion/configuracion.html.twig', [
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView()
        ]);
    }

}