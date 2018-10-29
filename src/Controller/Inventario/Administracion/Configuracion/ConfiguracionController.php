<?php

namespace App\Controller\Inventario\Administracion\Configuracion;


use App\Entity\Inventario\InvConfiguracion;
use App\Form\Type\Inventario\ConfiguracionType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfiguracionController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/configuracion/configuracion/lista", name="inventario_administracion_configuracion_configuracion_lista")
     */
    public function nuevo(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository(InvConfiguracion::class)->find(1);
        $form = $this->createForm(ConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arConfiguracion);
                $em->flush();
                Mensajes::info('Se guardo la configuración con éxito');
            }
        }
        return $this->render('inventario/administracion/configuracion/configuracion.html.twig', [
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView()
        ]);
    }

}

