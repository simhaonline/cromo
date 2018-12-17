<?php

namespace App\Controller\Cartera\Movimiento\Anticipo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarAnticipo;
use App\Entity\Cartera\CarAnticipoDetalle;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Cartera\AnticipoType;
use App\Form\Type\Cartera\ReciboType;
use App\Formato\Cartera\Recibo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnticipoController extends ControllerListenerGeneral
{
    protected $clase = CarAnticipo::class;
    protected $claseNombre = "CarAnticipo";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Anticipo";
    protected $nombre = "Anticipo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/anticipo/anticipo/lista", name="cartera_movimiento_anticipo_anticipo_lista")
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
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Recibos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarAnticipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_lista'));
            }
        }
        return $this->render('cartera/movimiento/anticipo/anticipo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/cartera/movimiento/anticipo/anticipo/nuevo/{id}", name="cartera_movimiento_anticipo_anticipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAnticipo = new CarAnticipo();
        if ($id != '0') {
            $arAnticipo = $em->getRepository(CarAnticipo::class)->find($id);
            if (!$arAnticipo) {
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_lista'));
            }
        } else {
            $arAnticipo->setFechaPago(new \DateTime('now'));
            $arAnticipo->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(AnticipoType::class, $arAnticipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        if ($id == 0) {
                            $arAnticipo->setFecha(new \DateTime('now'));
                        }
                        $arAnticipo->setClienteRel($arCliente);
                        $em->persist($arAnticipo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', ['id' => $arAnticipo->getCodigoAnticipoPk()]));
                    }
                }

            }
        }
        return $this->render('cartera/movimiento/anticipo/anticipo/nuevo.html.twig', [
            'arAnticipo' => $arAnticipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cartera/movimiento/anticipo/anticipo/detalle/{id}", name="cartera_movimiento_anticipo_anticipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAnticipo = $em->getRepository(CarAnticipo::class)->find($id);
        $form = Estandares::botonera($arAnticipo->getEstadoAutorizado(), $arAnticipo->getEstadoAprobado(), $arAnticipo->getEstadoAnulado());
        $arrBtnEliminarDetalle = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arAnticipo->getEstadoAutorizado()) {
            $arrBtnEliminarDetalle['disabled'] = true;
            $arrBtnActualizarDetalle['disabled'] = true;
        }
        $form->add('btnEliminarDetalle', SubmitType::class, $arrBtnEliminarDetalle);
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                if ($arAnticipo->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository(CarAnticipoDetalle::class)->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository(CarAnticipoDetalle::class)->liquidar($id);
                } else {
                    Mensajes::error('No se puede eliminar el registro, esta autorizado');
                }
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', array('id' => $id)));
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(CarAnticipoDetalle::class)->autorizar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arAnticipo->getEstadoAutorizado() == 1 && $arAnticipo->getEstadoImpreso() == 0) {
                    $em->getRepository(CarAnticipo::class)->desAutorizar($arAnticipo);
                    return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El recibo debe estar autorizado y no puede estar impreso");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarAnticipo::class)->aprobar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', ['id' => $id]));
            }
//            if ($form->get('btnImprimir')->isClicked()) {
//                $formato = new Recibo();
//                $formato->Generar($em, $id);
//            }
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                if ($arAnticipo->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $em->getRepository(CarAnticipoDetalle::class)->actualizarDetalle($arrControles, $arAnticipo);
                    return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("No se puede actualizar, el registro se encuentra autorizado");
                }
            }
        }

        $arAnticipoDetalle = $em->getRepository(CarAnticipoDetalle::class)->findBy(array('codigoAnticipoFk' => $id));
        return $this->render('cartera/movimiento/anticipo/anticipo/detalle.html.twig', array(
            'arAnticipo' => $arAnticipo,
            'arAnticipoDetalle' => $arAnticipoDetalle,
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
        $arAnticipo = $em->getRepository(CarAnticipo::class)->find($id);
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
}

