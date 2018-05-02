<?php

namespace App\Controller\Inventario\Movimiento;

use App\Controller\General\Mensajes;
use App\Entity\Inventario\InvSolicitud;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class SolicitudController extends Controller
{
    /**
     * @Route("/inv/movimiento/solicitud/lista", name="inv_mto_solicitud_lista")
     */
    public function lista(Request $request)
    {
        $respuesta = '';
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvSolicitud')->eliminar($arrSeleccionados);
                $objMensaje = $this->validarRespuesta($respuesta);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_lista'));
            }
        }
        $query = $em->getRepository(InvSolicitud::class)->lista();
        $arSolicitud = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/solicitud/lista.html.twig', [
            'arSolicitudes' => $arSolicitud,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inv/movimiento/solicitud/nuevo/{codigoSolicitud}", name="inv_mto_solicitud_nuevo")
     */
    public function nuevo(Request $request, $codigoSolicitud)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new \App\Entity\Inventario\InvSolicitud();
        if ($codigoSolicitud != 0) {
            $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($codigoSolicitud);
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
                return $this->redirect($this->generateUrl('inv_mto_solicitud_detalle', ['codigoSolicitud' => $arSolicitud->getCodigoSolicitudPk()]));
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
     * @Route("/inv/movimiento/solicitud/detalle/{codigoSolicitud}", name="inv_mto_solicitud_detalle")
     */
    public function detalle(Request $request, $codigoSolicitud)
    {
        $objMensaje = new Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($codigoSolicitud);
        if (!$arSolicitud) {
            return $this->redirect($this->generateUrl('inv_mto_solicitud_lista'));
        }
        $form = $this->formularioDetalle($arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvSolicitudDetalle')->eliminar($arSolicitud, $arrSeleccionados);
                $objMensaje = $objMensaje->validarRespuesta($respuesta);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_detalle', ['codigoSolicitud' => $codigoSolicitud]));
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvSolicitudDetalle')->autorizar($arSolicitud);
                $objMensaje = $objMensaje->validarRespuesta($respuesta);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_detalle', ['codigoSolicitud' => $codigoSolicitud]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvSolicitudDetalle')->desautorizar($arSolicitud);
                $objMensaje = $objMensaje->validarRespuesta($respuesta);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_detalle', ['codigoSolicitud' => $codigoSolicitud]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvSolicitudDetalle')->imprimir($arSolicitud);
                $objMensaje = $objMensaje->validarRespuesta($respuesta);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_detalle', ['codigoSolicitud' => $codigoSolicitud]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitudDetalle')->anular($arSolicitud);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_detalle', ['codigoSolicitud' => $codigoSolicitud]));
            }
        }
        $arSolicitudDetalles = $em->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $codigoSolicitud]);
        return $this->render('inventario/movimiento/solicitud/detalle.html.twig', [
            'arSolicitud' => $arSolicitud,
            'arSolicitudDetalles' => $arSolicitudDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inv/movimiento/solicitud/detalle/nuevo/{codigoDetalle}/{codigoSolicitud}", name="inv_mto_solicitud_detalle_nuevo")
     */
    public function nuevoDetalle(Request $request, $codigoDetalle, $codigoSolicitud)
    {
        $em = $this->getDoctrine()->getManager();

    }

    /**
     * @param $arSolicitud InvSolicitud
     * @return \Symfony\Component\Form\FormInterface
     */
    public function formularioDetalle($arSolicitud)
    {
        $arrBotonAutorizar = ['label' => 'Autorizar', 'disabled' => false];
        $arrBotonDesautorizar = ['label' => 'Desautorizar', 'disabled' => true];
        $arrBotonImprimir = ['label' => 'Imprimir', 'disabled' => true];
        $arrBotonAnular = ['label' => 'Anular', 'disabled' => true];
        $arrBotonEliminar = ['label' => 'Eliminar', 'disabled' => false];

        if ($arSolicitud->getEstadoAnulado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesautorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonEliminar['disabled'] = true;
        } elseif ($arSolicitud->getEstadoImpreso() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesautorizar['disabled'] = true;
            $arrBotonEliminar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
        } elseif ($arSolicitud->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesautorizar['disabled'] = false;
            $arrBotonImprimir['disabled'] = false;
            $arrBotonEliminar['disabled'] = true;
        } else {
            $arrBotonAutorizar['disabled'] = false;
            $arrBotonDesautorizar['disabled'] = true;
            $arrBotonEliminar['disabled'] = false;
            $arrBotonAnular['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        return $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, $arrBotonImprimir)
            ->add('btnAutorizar', SubmitType::class, $arrBotonAutorizar)
            ->add('btnDesautorizar', SubmitType::class, $arrBotonDesautorizar)
            ->add('btnAnular', SubmitType::class, $arrBotonAnular)
            ->add('btnEliminar', SubmitType::class, $arrBotonEliminar)
            ->getForm();
    }

    public function formularioLista()
    {
        return $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
    }
}
