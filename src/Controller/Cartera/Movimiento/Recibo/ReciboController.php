<?php

namespace App\Controller\Cartera\Movimiento\Recibo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Cartera\ReciboType;
use App\Formato\Cartera\Recibo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboController extends ControllerListenerGeneral
{
    protected $clase = CarRecibo::class;
    protected $claseNombre = "CarRecibo";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Recibo";
    protected $nombre = "Recibo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/recibo/recibo/lista", name="cartera_movimiento_recibo_recibo_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Recibos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarRecibo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        }
        return $this->render('cartera/movimiento/recibo/recibo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
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
                        $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                        if($txtCodigoTercero != '') {
                            $arTercero = $em->getRepository(FinTercero::class)->find($txtCodigoTercero);
                            $arRecibo->setTerceroRel($arTercero);
                        }
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
        return $this->render('cartera/movimiento/recibo/recibo/nuevo.html.twig', [
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

        $arDescuentosConceptos = $em->getRepository(CarDescuentoConcepto::class)->findAll();
        $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));
        return $this->render('cartera/movimiento/recibo/recibo/detalle.html.twig', array(
            'arRecibo'=> $arRecibo,
            'arReciboDetalle'=> $arReciboDetalle,
            'arDescuentoConceptos' => $arDescuentosConceptos,
            'clase' => array('clase' => 'CarRecibo', 'codigo' => $id),
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
                        $arReciboDetalle->setVrRetencionFuente($arCuentaCobrar->getVrRetencionFuente());
                        $saldo -= $arCuentaCobrar->getVrRetencionFuente();
                        $pagoAfectar = $arrControles['TxtSaldo' . $codigoCuentaCobrar];
                        $arReciboDetalle->setVrPago($saldo);
                        $arReciboDetalle->setVrPagoAfectar($pagoAfectar);
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
        return $this->render('cartera/movimiento/recibo/recibo/detalleNuevo.html.twig', array(
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
            return $this->render('cartera/movimiento/recibo/recibo/detalleaAplicar.html.twig', array(
                'arCuentasCobrar' => $arCuentasCobrar,
                'form' => $form->createView()));
        }
    }
}

