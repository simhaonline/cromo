<?php

namespace App\Controller\Transporte\Movimiento\Recogida\Despacho;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteRutaRecogida;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\DespachoRecogidaType;
use App\Entity\Transporte\TteDespachoRecogidaAuxiliar;
use App\Entity\Transporte\TteRecogida;
use App\Entity\Transporte\TteAuxiliar;
use App\Entity\Transporte\TteMonitoreo;
use App\Formato\Transporte\Despacho;
use App\Formato\Transporte\DespachoRecogida;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Count;

class DespachoRecogidaController extends ControllerListenerGeneral
{
    protected $clase = TteDespachoRecogida::class;
    protected $claseNombre = "TteDespachoRecogida";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Recogida";
    protected $nombre = "DespachoRecogida";

    /**
     * @Route("/transporte/movimiento/recogida/despacho/nuevo/{id}", name="transporte_movimiento_recogida_despachorecogida_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $arOperacion = $em->getRepository(TteOperacion::class)->find($this->getUser()->getCodigoOperacionFK());
            $arDespachoRecogida = new TteDespachoRecogida();
            $arDespachoRecogida->setFecha(new \DateTime('now'));
            $arDespachoRecogida->setOperacionRel($arOperacion);
            $arDespachoRecogida->setCiudadRel($arOperacion->getCiudadRel());

        } else {
            $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($id);
        }
        $form = $this->createForm(DespachoRecogidaType::class, $arDespachoRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoConductor = $request->request->get('txtCodigoConductor');
                if ($txtCodigoConductor != '') {
                    $arConductor = $em->getRepository(TteConductor::class)->find($txtCodigoConductor);
                    if ($arConductor) {
                        $txtCodigoVehiculo = $request->request->get('txtCodigoVehiculo');
                        if ($txtCodigoVehiculo != '') {
                            $arVehiculo = $em->getRepository(TteVehiculo::class)->find($txtCodigoVehiculo);
                            if ($arVehiculo) {
                                $arDespachoRecogida->setVehiculoRel($arVehiculo);
                                $arDespachoRecogida->setConductorRel($arConductor);
                                $arDespachoRecogida->setOperacionRel($this->getUser()->getOperacionRel());
                                $arrConfiguracionLiquidarDespacho = $em->getRepository(TteConfiguracion::class)->liquidarDespacho();

                                $descuentos = $arDespachoRecogida->getVrDescuentoPapeleria() + $arDespachoRecogida->getVrDescuentoSeguridad() + $arDespachoRecogida->getVrDescuentoCargue() + $arDespachoRecogida->getVrDescuentoEstampilla();
                                $retencionFuente = 0;
                                if ($arDespachoRecogida->getVrFletePago() > $arrConfiguracionLiquidarDespacho['vrBaseRetencionFuente']) {
                                    $retencionFuente = $arDespachoRecogida->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeRetencionFuente'] / 100;
                                }
                                $industriaComercio = $arDespachoRecogida->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeIndustriaComercio'] / 100;

                                $total = $arDespachoRecogida->getVrFletePago() - ($arDespachoRecogida->getVrAnticipo() + $retencionFuente + $industriaComercio);
                                $saldo = $total - $descuentos;
                                $arDespachoRecogida->setVrIndustriaComercio($industriaComercio);
                                $arDespachoRecogida->setVrRetencionFuente($retencionFuente);
                                $arDespachoRecogida->setVrTotal($total);
                                $arDespachoRecogida->setVrSaldo($saldo);
                                $arDespachoRecogida->setUsuario($this->getUser()->getUsername());

                                $em->persist($arDespachoRecogida);
                                $em->flush();
                                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despachorecogida_detalle', ['id' => $arDespachoRecogida->getCodigoDespachoRecogidaPk()]));
                            } else {
                                Mensajes::error('No se ha encontrado un vehiculo con el codigo ingresado');
                            }
                        } else {
                            Mensajes::error('Debe seleccionar un vehiculo');
                        }
                    } else {
                        Mensajes::error('No se ha encontrado un conductor con el codigo ingresado');
                    }
                } else {
                    Mensajes::error('Debe seleccionar un conductor');
                }

            }
        }
        return $this->render('transporte/movimiento/recogida/despacho/nuevo.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/recogida/despacho/lista", name="transporte_movimiento_recogida_despacho_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Despacho recogidas");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteDespachoRecogida::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despacho_lista'));
            }
        }

        return $this->render('transporte/movimiento/recogida/despacho/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/transporte/movimiento/recogida/despacho/detalle/{id}", name="transporte_movimiento_recogida_despachorecogida_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($id);
        $form = Estandares::botonera($arDespachoRecogida->getEstadoAutorizado(), $arDespachoRecogida->getEstadoAprobado(), $arDespachoRecogida->getEstadoAnulado());
        $arrBtnEliminarRecogida = ['label' => 'Eliminar', 'disabled' => false];
        $arrBtnEliminarAuxiliar = ['label' => 'Eliminar', 'disabled' => false];
        if ($arDespachoRecogida->getEstadoAutorizado()) {
            $arrBtnEliminarRecogida['disabled'] = true;
            $arrBtnEliminarAuxiliar['disabled'] = true;
        }
        $form->add('btnEliminarRecogida', SubmitType::class, $arrBtnEliminarRecogida)
            ->add('btnEliminarAuxiliar', SubmitType::class, $arrBtnEliminarAuxiliar)
            ->add('btnMonitoreo', SubmitType::class, array('label' => 'Monitoreo'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new DespachoRecogida();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnMonitoreo')->isClicked()) {
                if (!$arDespachoRecogida->getEstadoMonitoreo()) {
                    $respuesta = $this->getDoctrine()->getRepository(TteMonitoreo::class)->generar(
                        $arDespachoRecogida->getCodigoVehiculoFk());
                    $arDespachoRecogida->setEstadoMonitoreo(1);
                    if ($respuesta) {
                        $em->persist($arDespachoRecogida);
                        $em->flush();
                    }
                }
            }
            if ($form->get('btnEliminarRecogida')->isClicked()) {
                $arrRecogidas = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespachoRecogida::class)->retirarRecogida($arrRecogidas);
                if ($respuesta) {
                    $em->flush();
                    $em->getRepository(TteDespachoRecogida::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despachorecogida_detalle', array('id' => $id)));
            }
            if ($form->get('btnEliminarAuxiliar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarAuxiliar');
                $em->getRepository(TteDespachoRecogidaAuxiliar::class)->eliminar($arrSeleccionados, $id);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despachorecogida_detalle', array('id' => $id)));
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteDespachoRecogida::class)->autorizar($arDespachoRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despachorecogida_detalle', ['id' => $arDespachoRecogida->getCodigoDespachoRecogidaPk()]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteDespachoRecogida::class)->desautorizar($arDespachoRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despachorecogida_detalle', ['id' => $arDespachoRecogida->getCodigoDespachoRecogidaPk()]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteDespachoRecogida::class)->aprobar($arDespachoRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despachorecogida_detalle', ['id' => $arDespachoRecogida->getCodigoDespachoRecogidaPk()]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(TteDespachoRecogida::class)->anular($arDespachoRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despachorecogida_detalle', ['id' => $arDespachoRecogida->getCodigoDespachoRecogidaPk()]));
            }
        }
        $arRecogidas = $this->getDoctrine()->getRepository(TteRecogida::class)->despacho($id);
        $arDespachoRecogidaAuxiliares = $this->getDoctrine()->getRepository(TteDespachoRecogidaAuxiliar::class)->despacho($id);
        return $this->render('transporte/movimiento/recogida/despacho/detalle.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'arRecogidas' => $arRecogidas,
            'arDespachoRecogidaAuxiliares' => $arDespachoRecogidaAuxiliares,
            'clase' => array('clase' => 'TteDespachoRecogida', 'codigo' => $id),
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/despacho/detalle/adicionar/recogida/{codigoDespachoRecogida}", name="transporte_movimiento_recogida_despacho_detalle_adicionar_recogida")
     */
    public function detalleAdicionarRecogida(Request $request, $codigoDespachoRecogida)
    {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('cboRutaRecogida', EntityType::class, $em->getRepository(TteRutaRecogida::class)->llenarCombo())
            ->add('ChkMostrarSoloRecogidasRuta', CheckboxType::class, array('label' => false, 'required' => false, 'data' => $session->get('filtroTteMostrarSoloRecogidasRuta')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteMostrarSoloRecogidasRuta', $form->get('ChkMostrarSoloRecogidasRuta')->getData());
                $rutaRecogida = $form->get('cboRutaRecogida')->getData();
                if ($rutaRecogida != '') {
                    $session->set('filtroTteCodigoRutaRecogida', $form->get('cboRutaRecogida')->getData()->getCodigoRutaRecogidaPk());
                } else {
                    $session->set('filtroTteCodigoRutaRecogida', null);
                }
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                        $arRecogida->setDespachoRecogidaRel($arDespachoRecogida);
                        $arRecogida->setEstadoProgramado(1);
                        $em->persist($arRecogida);
                    }
                    $em->flush();
                }
                $em->getRepository(TteDespachoRecogida::class)->liquidar($codigoDespachoRecogida);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arRecogidas = $this->getDoctrine()->getRepository(TteRecogida::class)->despachoPendiente($arDespachoRecogida->getFecha()->format('Y-m-d'), $arDespachoRecogida->getCodigoRutaRecogidaFk());
        if (count($arRecogidas) == 0) {
            Mensajes::error('No hay recogidas autorizadas y aprobadas pendientes por asignar');
        }
        return $this->render('transporte/movimiento/recogida/despacho/detalleAdicionarRecogida.html.twig', ['arRecogidas' => $arRecogidas, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/despacho/detalle/adicionar/auxiliar/{codigoDespachoRecogida}", name="transporte_movimiento_recogida_despacho_detalle_adicionar_auxiliar")
     */
    public function detalleAdicionarAuxiliar(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arAuxiliar = $em->getRepository(TteAuxiliar::class)->find($codigo);
                    $arDespachoRecogidaAuxiliar = new TteDespachoRecogidaAuxiliar();
                    $arDespachoRecogidaAuxiliar->setAuxiliarRel($arAuxiliar);
                    $arDespachoRecogidaAuxiliar->setDespachoRecogidaRel($arDespachoRecogida);
                    $em->persist($arDespachoRecogidaAuxiliar);
                }
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arAuxiliares = $this->getDoctrine()->getRepository(TteAuxiliar::class)->findAll();
        return $this->render('transporte/movimiento/recogida/despacho/detalleAdicionarAuxiliar.html.twig', ['arAuxiliares' => $arAuxiliares, 'form' => $form->createView()]);
    }

    private function actualizarDetalle($arrControles, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $codigo) {
                $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                if ($arrControles['TxtUnidades' . $codigo] != '') {
                    $arRecogida->setUnidades($arrControles['TxtUnidades' . $codigo]);
                }
                if ($arrControles['TxtPesoReal' . $codigo] != '') {
                    $arRecogida->setPesoReal($arrControles['TxtPesoReal' . $codigo]);
                }
                if ($arrControles['TxtPesoVolumen' . $codigo] != '') {
                    $arRecogida->setPesoVolumen($arrControles['TxtPesoVolumen' . $codigo]);
                }
                $em->persist($arRecogida);
            }
            $em->flush();
        }
    }

}

