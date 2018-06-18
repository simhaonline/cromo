<?php

namespace App\Controller\Inventario\Movimiento\Inventario;

use App\Entity\Inventario\InvMovimiento;
use App\Form\Type\Inventario\MovimientoType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class MovimientoController extends Controller
{
    var $query = '';

    /**
     * @Route("/inv/mov/inventario/movimiento/lista/documentos/{tipo}", name="inv_mov_inventario_movimiento_documentos_lista")
     */
    public function listaDocumentos(Request $request, $tipo)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumentos = $em->getRepository('App:Inventario\InvDocumento')->findBy(['codigoDocumentoTipoFk' => $tipo]);
        return $this->render('inventario/movimiento/inventario/listaDocumentos.html.twig', [
            'arDocumentos' => $arDocumentos
        ]);
    }

    /**
     * @Route("/inv/mov/inventario/movimiento/lista/movimientos/{codigoDocumento}", name="inv_mov_inventario_movimiento_lista")
     */
    public function listaMovimientos(Request $request, $codigoDocumento)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimientos = $em->getRepository('App:Inventario\InvMovimiento')->findBy(['codigoDocumentoFk' => $codigoDocumento]);
        return $this->render('inventario/movimiento/inventario/listaMovimientos.html.twig', [
            'arMovimientos' => $arMovimientos,
            'codigoDocumento' => $codigoDocumento
        ]);
    }

    /**
     * @Route("/inv/mov/inventario/movimiento/nuevo/{codigoDocumento}/{id}", name="inv_mov_inventario_movimiento_nuevo")
     */
    public function nuevo(Request $request, $codigoDocumento, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new InvMovimiento();
        if ($id != 0) {
            $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find($id);
            if (!$arMovimiento) {
                return $this->redirect($this->generateUrl('inv_mov_inventario_movimiento_lista', ['codigoDocumento' => $codigoDocumento]));
            }
        }
        $arMovimiento->setFecha(new \DateTime('now'));
        $arMovimiento->setUsuario($this->getUser()->getUserName());
        $arDocumento = $em->getRepository('App:Inventario\InvDocumento')->find($codigoDocumento);
        $arMovimiento->setDocumentoRel($arDocumento);
        $form = $this->createForm(MovimientoType::class, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arMovimiento->setFecha(new \DateTime('now'));
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('inv_mov_inventario_movimiento_detalle', ['id' => $arMovimiento->getCodigoMovimientoPk()]));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('inv_mov_inventario_movimiento_nuevo', ['id' => 0]));
            }
        }
        return $this->render('inventario/movimiento/inventario/nuevo.html.twig', [
            'form' => $form->createView(),
            'arMovimiento' => $arMovimiento
        ]);
    }

    /**
     * @Route("/inv/mov/inventario/movimiento/detalle/{id}", name="inv_mov_inventario_movimiento_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find($id);
        $arMovimientoDetalles = $em->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $id]);
        $form = $this->formularioDetalles($arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->autorizar($arMovimiento);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->desautorizar($arMovimiento);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
//                $objFormatoSolicitud->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->aprobar($arMovimiento);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->anular($arMovimiento);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvSolicitudDetalle')->eliminar($arMovimiento, $arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle', ['id' => $id]));
            }
        }
        return $this->render('inventario/movimiento/inventario/detalle.html.twig', [
            'form' => $form->createView(),
            'arMovimientoDetalles' => $arMovimientoDetalles,
            'arMovimiento' => $arMovimiento
        ]);
    }

    /**
     * @Route("/inv/mov/inventario/solicitud/detalle/nuevo/{id}", name="inv_mov_inventario_solicitud_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arMovimiento = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
        $form = $this->formularioFiltroItems();
        $form->handleRequest($request);
        $this->listaItems($em, $form);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $this->listaItems($em, $form);
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository('App:Inventario\InvItem')->find($codigoItem);
                            $arMovimientoDetalle = new InvSolicitudDetalle();
                            $arMovimientoDetalle->setSolicitudRel($arMovimiento);
                            $arMovimientoDetalle->setItemRel($arItem);
                            $arMovimientoDetalle->setCantidadSolicitada($cantidad);
                            $arMovimientoDetalle->setCantidadRestante($cantidad);
                            $em->persist($arMovimientoDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/solicitud/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    /**
     * @param $arMovimiento InvSolicitud
     * @return \Symfony\Component\Form\FormInterface
     */
    private function formularioDetalles($arMovimiento)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arMovimiento->getEstadoAnulado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($arMovimiento->getEstadoAprobado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = false;
            $arrBtnAnular['disabled'] = false;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($arMovimiento->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            $arrBtnImprimir['disabled'] = false;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = false;
        } else {
            $arrBtnAutorizar['disabled'] = false;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = false;
            $arrBtnAprobar['disabled'] = true;
        }
        return $this
            ->createFormBuilder()
            ->add('btnAutorizar', SubmitType::class, $arrBtnAutorizar)
            ->add('btnAprobar', SubmitType::class, $arrBtnAprobar)
            ->add('btnDesautorizar', SubmitType::class, $arrBtnDesautorizar)
            ->add('btnImprimir', SubmitType::class, $arrBtnImprimir)
            ->add('btnAnular', SubmitType::class, $arrBtnAnular)
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->getForm();
    }

    private function formularioFiltroItems()
    {
        return $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
    }

    /**
     * @param $em ObjectManager
     * @param $form \Symfony\Component\Form\FormInterface
     */
    private function listaItems($em, $form)
    {
        $session = new Session();
        $session->set('filtroCodigoItem', $form->get('txtCodigoItem')->getData());
        $session->set('filtroNombreItem', $form->get('txtNombreItem')->getData());
        $this->query = $em->getRepository('App:Inventario\InvItem')->listarItems($session->get('filtroNombreItem'), $session->get('filtroCodigoItem'));
    }
}
