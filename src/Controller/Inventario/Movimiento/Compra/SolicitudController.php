<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Formato\Inventario\Solicitud;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class SolicitudController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inv/mto/inventario/solicitud/lista", name="inventario_movimiento_inventario_solicitud_lista")
     * @throws \Doctrine\ORM\ORMException
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('numero', NumberType::class, ['required' => false, 'data' => $session->get('filtroInvNumeroSolicitud')])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvEstadoAprobado'), 'required' => false])
            ->add('solicitudTipoRel', EntityType::class, $em->getRepository('App:Inventario\InvSolicitudTipo')->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvNumeroSolicitud', $form->get('numero')->getData());
                $session->set('filtroInvEstadoAprobado', $form->get('estadoAprobado')->getData());
                $solicitudTipo = $form->get('solicitudTipoRel')->getData();
                if($solicitudTipo != ''){
                    $session->set('filtroInvCodigoSolicitudTipo', $form->get('solicitudTipoRel')->getData()->getCodigoSolicitudTipoPk());
                } else {
                    $session->set('filtroInvCodigoSolicitudTipo', null);
                }
            }
        }
        $query = $this->getDoctrine()->getManager()->getRepository('App:Inventario\InvSolicitud')->
        listaSolicitud();
        $arSolicitudes = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/solicitud/lista.html.twig', [
            'form' => $form->createView(),
            'arSolicitudes' => $arSolicitudes
        ]);
    }

    /**
     * @Route("/inv/mto/inventario/solicitud/nuevo/{id}", name="inventario_movimiento_inventario_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new InvSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'inventario', 'entidad' => 'solicitud']));
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
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => $arSolicitud->getCodigoSolicitudPk()]));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSolicitud);
                $em->flush($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => 0]));
            }
        }
        return $this->render('inventario/movimiento/compra/solicitud/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arSolicitud
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inv/mto/inventario/solicitud/detalle/{id}", name="inventario_movimiento_inventario_solicitud_detalle")
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function detalle(Request $request, $id)
    {
        $objFormatoSolicitud = new Solicitud();
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
        $arSolicitudDetalles = $em->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $id]);
        $form = Estandares::botonera($arSolicitud->getEstadoAutorizado(),$arSolicitud->getEstadoAprobado(),$arSolicitud->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->autorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->desautorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatoSolicitud->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->aprobar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvSolicitud')->anular($arSolicitud);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvSolicitudDetalle')->eliminar($arSolicitud, $arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => $id]));
            }
        }
        return $this->render('inventario/movimiento/compra/solicitud/detalle.html.twig', [
            'form' => $form->createView(),
            'arSolicitudDetalles' => $arSolicitudDetalles,
            'arSolicitud' => $arSolicitud
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/inv/mto/inventario/solicitud/detalle/nuevo/{id}", name="inventario_movimiento_inventario_solicitud_detalle_nuevo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvCodigoItem', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvNombreItem', $form->get('txtNombreItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository('App:Inventario\InvItem')->find($codigoItem);
                            $arSolicitudDetalle = new InvSolicitudDetalle();
                            $arSolicitudDetalle->setSolicitudRel($arSolicitud);
                            $arSolicitudDetalle->setItemRel($arItem);
                            $arSolicitudDetalle->setCantidad($cantidad);
                            $arSolicitudDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arSolicitudDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->getRepository('App:Inventario\InvItem')->listarItems();
        $arItems = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/solicitud/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }
}
