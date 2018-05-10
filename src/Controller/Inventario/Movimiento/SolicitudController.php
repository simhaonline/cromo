<?php

namespace App\Controller\Inventario\Movimiento;

use App\Entity\Inventario\InvSolicitud;
use App\Estructura\AdministracionController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class SolicitudController extends Controller
{
    var $query = '';

    /**
     * @Route("/inv/movimiento/solicitud/nuevo/{id}", name="inv_mto_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new InvSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('inv_mto_solicitud_lista'));
            }
        }
        $arSolicitud->setFecha(new \DateTime('now'));
        $arSolicitud->setUsuario($this->getUser()->getUserName());
        $form = $this->createForm(SolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSolicitud->setFecha(new \DateTime('now'));
                $em->persist($arSolicitud);
                $em->flush($arSolicitud);
                return $this->redirect($this->generateUrl('listado', ['entidad' => 'InvSolicitud']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSolicitud);
                $em->flush($arSolicitud);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_nuevo', ['codigoSolicitud' => 0]));
            }
        }
        return $this->render('inventario/movimiento/solicitud/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arSolicitud
        ]);
    }

    /**
     * @Route("/inv/movimiento/solicitud/detalle/nuevo/{id}", name="inv_mto_solicitud_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
    }
}
