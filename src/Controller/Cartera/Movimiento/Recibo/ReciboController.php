<?php

namespace App\Controller\Cartera\Movimiento\Recibo;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Transporte\TteRecibo;
use App\Form\Type\Cartera\ReciboType;
use App\Formato\Cartera\Recibo;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboController extends Controller
{
    /**
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
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroNumero')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroNumero', $form->get('txtNumero')->getData());
            if ($form->get('txtCodigoCliente')->getData() != '') {
                $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            } else {
                $session->set('filtroTteCodigoCliente', null);
                $session->set('filtroTteNombreCliente', null);
            }
        }
        $arRecibo = $paginator->paginate($em->getRepository(CarRecibo::class)->lista(), $request->query->getInt('page', 1),20);
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
        }
        $arRecibo->setFecha(new \DateTime('now'));
        $arRecibo->setFechaPago(new \DateTime('now'));
        $form = $this->createForm(ReciboType::class, $arRecibo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arRecibo);
                $em->flush();
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $arRecibo->getCodigoReciboPk()]));
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
            if($arRecibo->getEstadoAutorizado() == 0){
                $error = false;
                $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));
                if (count($em->getRepository(CarReciboDetalle::class)->findBy(['codigoReciboFk' => $arRecibo->getCodigoReciboPk()])) > 0){
                    foreach ($arReciboDetalles AS $arReciboDetalle) {
                        if ($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                            $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                            if ($arCuentaCobrarAplicacion->getVrSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                                $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                                $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                                $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                                $arCuentaCobrarAplicacion->setvRSaldoOperado($saldoOperado);
                                $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                                $em->persist($arCuentaCobrarAplicacion);
                                //Cuenta por cobrar
                                $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                                $saldo = $arCuentaCobrar->getVrSaldo() - $arReciboDetalle->getVrPagoAfectar();
                                $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                                $arCuentaCobrar->setVrSaldo($saldo);
                                $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                                $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $arReciboDetalle->getVrPagoAfectar());
                                $em->persist($arCuentaCobrar);
                            } else {
                                Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaCobrarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaCobrarAplicacion->getVrSaldo());
                                $error = true;
                                break;
                            }

                        } else {
                            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                            $saldo = $arCuentaCobrar->getVrSaldo() - $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                            $arCuentaCobrar->setVrSaldo($saldo);
                            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrar);
                        }
                    }
                    if($error == false){
                        $arRecibo->setEstadoAutorizado(1);
                        $em->persist($arRecibo);
                        $em->flush();
                    }
                } else {
                    Mensajes::error("No se puede autorizar un recibo sin detalles");
                }
            }
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
                        $vrPago = $em->getRepository(CarReciboDetalle::class)->vrPagoRecibo($codigoCuentaCobrar, $id);
                        $saldo = $arrControles['TxtSaldo' . $codigoCuentaCobrar] - $vrPago;
                        $arReciboDetalle = new CarReciboDetalle();
                        $arReciboDetalle->setReciboRel($arRecibo);
                        $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arReciboDetalle->setVrPago($saldo);
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

