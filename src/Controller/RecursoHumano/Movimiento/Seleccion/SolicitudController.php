<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion;


use App\Entity\RecursoHumano\RhuSolicitud;
use App\Form\Type\RecursoHumano\SolicitudType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SolicitudController extends Controller
{
    /**
     * @Route("rhu/mov/seleccion/solicitud/nuevo/{id}", name="rhu_mov_seleccion_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new RhuSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository('App:RecursoHumano\RhuSolicitud')->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'solicitud']));
            }
        }
        $form = $this->createForm(SolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSolicitud->setFecha(new \DateTime('now'));
                $em->persist($arSolicitud);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano','entidad' => 'solicitud']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSolicitud);
                $em->flush($arSolicitud);
                return $this->redirect($this->generateUrl('rhu_mov_seleccion_solicitud_nuevo', ['codigoSolicitud' => 0]));
            }
        }
        return $this->render('recursoHumano/movimiento/solicitud/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arSolicitud
        ]);
    }
}

