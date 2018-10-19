<?php

namespace App\Controller\Cartera\Movimiento\Recibo;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Transporte\TteRecibo;
use App\Form\Type\Cartera\ReciboType;
use App\Formato\Cartera\Recibo;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class ReciboController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/recibo/recibo/lista", name="cartera_movimiento_recibo_recibo_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroCarReciboNumero')])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroCarReciboEstadoAprobado'), 'required' => false])
            ->add('cboReciboTipo', EntityType::class, $em->getRepository(CarReciboTipo::class)->llenarCombo())
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroCarReciboNumero', $form->get('txtNumero')->getData());
                $session->set('filtroCarReciboEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
                if ($form->get('txtCodigoCliente')->getData() != '') {
                    $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroCarNombreCliente', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroCarCodigoCliente', null);
                    $session->set('filtroCarNombreCliente', null);
                }
                $arReciboTipo = $form->get('cboReciboTipo')->getData();
                if ($arReciboTipo) {
                    $session->set('filtroCarReciboTipo', $arReciboTipo->getCodigoReciboTipoPk());
                } else {
                    $session->set('filtroCarReciboTipo', null);
                }
                $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
            }
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarRecibo::class)->eliminar($arrSeleccionados);
            }
        }

        $arRecibo = $paginator->paginate($em->getRepository(CarRecibo::class)->lista(), $request->query->getInt('page', 1),30);
        return $this->render('cartera/movimiento/recibo/lista.html.twig',
            ['arRecibo' => $arRecibo,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/cartera/movimiento/recibo/recibo/nuevo/{id}", name="cartera_movimiento_recibo_recibo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecibo = new CarRecibo();
        if ($id != '0') {
            $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
            if (!$arRecibo) {
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        } else {
            $arRecibo->setFechaPago(new \DateTime('now'));
            $arRecibo->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(ReciboType::class, $arRecibo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        if ($id == 0) {
                            $arRecibo->setFecha(new \DateTime('now'));
                        }
                        $arRecibo->setClienteRel($arCliente);
                        $em->persist($arRecibo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $arRecibo->getCodigoReciboPk()]));
                    }
                }

            }
        }
        return $this->render('cartera/movimiento/recibo/nuevo.html.twig', [
            'arRecibo' => $arRecibo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cartera/movimiento/recibo/recibo/detalle/{id}", name="cartera_movimiento_recibo_recibo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $form = Estandares::botonera($arRecibo->getEstadoAutorizado(),$arRecibo->getEstadoAprobado(),$arRecibo->getEstadoAnulado());
        $arrBtnEliminarDetalle = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arRecibo->getEstadoAutorizado()){
            $arrBtnEliminarDetalle['disabled'] = true;
            $arrBtnActualizarDetalle['disabled'] = true;
        }
        $form->add('btnEliminarDetalle', SubmitType::class, $arrBtnEliminarDetalle);
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository(CarReciboDetalle::class)->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository(CarReciboDetalle::class)->liquidar($id);
                } else {
                    Mensajes::error('No se puede eliminar el registro, esta autorizado');
                }
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', array('id' => $id)));
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(CarRecibo::class)->autorizar($arRecibo);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 1 && $arRecibo->getEstadoImpreso() == 0) {
                    $em->getRepository(CarRecibo::class)->desAutorizar($arRecibo);
                    return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
                }else {
                    Mensajes::error("El recibo debe estar autorizado y no puede estar impreso");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarRecibo::class)->aprobar($arRecibo);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Recibo();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $em->getRepository(CarReciboDetalle::class)->actualizarDetalle($arrControles, $arRecibo);
                    return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("No se puede actualizar, el registro se encuentra autorizado");
                }
            }
        }

        $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));
        return $this->render('cartera/movimiento/recibo/detalle.html.twig', array(
            'arRecibo'=> $arRecibo,
            'arReciboDetalle'=> $arReciboDetalle,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * * @Route("/cartera/movimiento/recibo/recibo/detalle/nuevo/{id}", name="cartera_movimiento_recibo_recibo_detalle_nuevo")
     * @throws \Doctrine\ORM\ORMException
     */
    public function detalleNuevo(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        //Lo quito mario porque no sabia que era
                        //$vrPago = $em->getRepository(CarReciboDetalle::class)->vrPagoRecibo($codigoCuentaCobrar, $id);
                        //$saldo = $arrControles['TxtSaldo' . $codigoCuentaCobrar] - $vrPago;
                        $saldo = $arrControles['TxtSaldo' . $codigoCuentaCobrar];
                        $arReciboDetalle = new CarReciboDetalle();
                        $arReciboDetalle->setReciboRel($arRecibo);
                        $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arReciboDetalle->setVrPago($saldo);
                        $arReciboDetalle->setVrPagoAfectar($saldo);
                        $arReciboDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                        $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $arReciboDetalle->setOperacion(1);
                        $em->persist($arReciboDetalle);
                    }
                    $em->flush();
                }
                $em->getRepository(CarReciboDetalle::class)->liquidar($id);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->cuentasCobrar($arRecibo->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
        return $this->render('cartera/movimiento/recibo/detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'arRecibo' => $arRecibo,
            'form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * * @Route("/cartera/movimiento/recibo/recibo/detalle/aplicar/{id}", name="cartera_movimiento_recibo_recibo_detalle_aplicar")
     * @throws \Doctrine\ORM\ORMException
     */
    public function detalleNuevoAplicar(Request $request, $id)
    {
        {
            $em = $this->getDoctrine()->getManager();
            $paginator = $this->get('knp_paginator');
            $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->find($id);
            $form = $this->createFormBuilder()
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    if ($request->request->get('OpAplicar')) {
                        set_time_limit(0);
                        ini_set("memory_limit", -1);
                        $codigoCuentaCobrar = $request->request->get('OpAplicar');
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        $arReciboDetalle->setCuentaCobrarAplicacionRel($arCuentaCobrar);
                        $arReciboDetalle->setNumeroDocumentoAplicacion($arCuentaCobrar->getNumeroDocumento());
                        $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $arReciboDetalle->setOperacion(0);
                        $arReciboDetalle->setVrPago(round($arCuentaCobrar->getVrSaldo()));
                        $arReciboDetalle->setVrPagoAfectar(round($arCuentaCobrar->getVrSaldo()));
                        $em->persist($arReciboDetalle);
                        $em->flush();
                        $em->getRepository(CarReciboDetalle::class)->liquidar($arReciboDetalle->getCodigoReciboFk());
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
            $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->cuentasCobrarAplicar($arReciboDetalle->getReciboRel()->getCodigoClienteFk());
            $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
            return $this->render('cartera/movimiento/recibo/detalleaAplicar.html.twig', array(
                'arCuentasCobrar' => $arCuentasCobrar,
                'form' => $form->createView()));
        }
    }
}

