<?php

namespace App\Controller\Cartera\Movimiento\Compromiso;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCompromiso;
use App\Entity\Cartera\CarCompromisoDetalle;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarIngresoConcepto;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Cartera\CompromisoType;
use App\Form\Type\Cartera\ReciboType;
use App\Formato\Cartera\Recibo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CompromisoController extends ControllerListenerGeneral
{
    protected $clase = CarCompromiso::class;
    protected $claseNombre = "CarCompromiso";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Compromiso";
    protected $nombre = "Compromiso";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/compromiso/compromiso/lista", name="cartera_movimiento_compromiso_compromiso_lista")
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
                $em->getRepository(CarCompromiso::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_compromiso_compromiso_lista'));
            }
        }
        return $this->render('cartera/movimiento/compromiso/compromiso/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/cartera/movimiento/compromiso/compromiso/nuevo/{id}", name="cartera_movimiento_compromiso_compromiso_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCompromiso = new CarCompromiso();
        if ($id != '0') {
            $arCompromiso = $em->getRepository(CarCompromiso::class)->find($id);
            if (!$arCompromiso) {
                return $this->redirect($this->generateUrl('cartera_movimiento_compromiso_compromiso_lista'));
            }
        } else {
            $arCompromiso->setFecha(new \DateTime('now'));
            $arCompromiso->setFechaCompromiso(new \DateTime('now'));
            $arCompromiso->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(CompromisoType::class, $arCompromiso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arCompromiso->setClienteRel($arCliente);
                        if ($id == 0) {
                            $arCompromiso->setFecha(new \DateTime('now'));
                        }
                        $em->persist($arCompromiso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_compromiso_compromiso_detalle', ['id' => $arCompromiso->getCodigoCompromisoPk()]));
                    }
                }

            }
        }
        return $this->render('cartera/movimiento/compromiso/compromiso/nuevo.html.twig', [
            'arCompromiso' => $arCompromiso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/cartera/movimiento/compromiso/compromiso/detalle/{id}", name="cartera_movimiento_compromiso_compromiso_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCompromiso = $em->getRepository(CarCompromiso::class)->find($id);
        $form = Estandares::botonera($arCompromiso->getEstadoAutorizado(), $arCompromiso->getEstadoAprobado(), $arCompromiso->getEstadoAnulado());
        $arrBtnEliminarDetalle = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arCompromiso->getEstadoAutorizado()) {
            $arrBtnEliminarDetalle['disabled'] = true;
        }
        $form->add('btnEliminarDetalle', SubmitType::class, $arrBtnEliminarDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                if ($arCompromiso->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository(CarCompromisoDetalle::class)->eliminar($arrSeleccionados);
                } else {
                    Mensajes::error('No se puede eliminar el registro, esta autorizado');
                }
                return $this->redirect($this->generateUrl('cartera_movimiento_compromiso_compromiso_detalle', array('id' => $id)));
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(CarCompromiso::class)->autorizar($arCompromiso);
                return $this->redirect($this->generateUrl('cartera_movimiento_compromiso_compromiso_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arCompromiso->getEstadoAutorizado() == 1) {
                    $em->getRepository(CarCompromiso::class)->desAutorizar($arCompromiso);
                    return $this->redirect($this->generateUrl('cartera_movimiento_compromiso_compromiso_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El recibo debe estar autorizado");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarCompromiso::class)->aprobar($arCompromiso);
                return $this->redirect($this->generateUrl('cartera_movimiento_compromiso_compromiso_detalle', ['id' => $id]));
            }
//            if ($form->get('btnImprimir')->isClicked()) {
//                $formato = new Recibo();
//                $formato->Generar($em, $id);
//            }
//            if ($form->get('btnAnular')->isClicked()) {
//                $em->getRepository(CarCompromiso::class)->anular($arCompromiso);
//                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
//            }
        }
        $arCompromisoDetalles = $em->getRepository(CarCompromisoDetalle::class)->findBy(array('codigoCompromisoFk' => $id));
        return $this->render('cartera/movimiento/compromiso/compromiso/detalle.html.twig', array(
            'arCompromiso' => $arCompromiso,
            'arCompromisoDetalles' => $arCompromisoDetalles,
            'clase' => array('clase' => 'CarCompromiso', 'codigo' => $id),
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * * @Route("/cartera/movimiento/compromiso/compromiso/detalle/nuevo/{id}", name="cartera_movimiento_compromiso_compromiso_detalle_nuevo")
     * @throws \Doctrine\ORM\ORMException
     */
    public function detalleNuevo(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arCompromiso = $em->getRepository(CarCompromiso::class)->find($id);
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
//                        $saldo = $arrControles['TxtSaldo' . $codigoCuentaCobrar];
                        $arCompromisoDetalle = new CarCompromisoDetalle();
                        $arCompromisoDetalle->setCompromisoRel($arCompromiso);
                        $arCompromisoDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $em->persist($arCompromisoDetalle);
                    }
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->cuentasCobrarCompromiso($arCompromiso->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
        return $this->render('cartera/movimiento/compromiso/compromiso/detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'arCompromiso' => $arCompromiso,
            'form' => $form->createView()));
    }

}

