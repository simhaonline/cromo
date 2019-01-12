<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\DespachoLiquidarType;
use App\Form\Type\Transporte\DespachoType;
use App\Form\Type\Transporte\NovedadType;
use App\Formato\Transporte\CobroEntrega;
use App\Formato\Transporte\Despacho;
use App\Formato\Transporte\Liquidacion;
use App\Formato\Transporte\Manifiesto;
use App\Formato\Transporte\RelacionEntrega;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DespachoController extends ControllerListenerGeneral
{
    protected $clase= TteDespacho::class;
    protected $claseNombre = "TteDespacho";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Despacho";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/transporte/despacho/lista", name="transporte_movimiento_transporte_despacho_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
//                $datos = $this->getDatosLista();
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Despacho");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteDespacho::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_lista'));
            }
        }

        return $this->render('transporte/movimiento/transporte/despacho/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/transporte/movimiento/transporte/despacho/nuevo/{id}", name="transporte_movimiento_transporte_despacho_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = new TteDespacho();
        if ($id != 0) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        }
        $form = $this->createForm(DespachoType::class, $arDespacho);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arDespacho = $form->getData();
                $txtCodigoConductor = $request->request->get('txtCodigoConductor');
                if ($txtCodigoConductor != '') {
                    $arConductor = $em->getRepository(TteConductor::class)->find($txtCodigoConductor);
                    if ($arConductor) {
                        $txtCodigoVehiculo = $request->request->get('txtCodigoVehiculo');
                        if ($txtCodigoVehiculo != '') {
                            $arVehiculo = $em->getRepository(TteVehiculo::class)->find($txtCodigoVehiculo);
                            if ($arVehiculo) {
                                $arrConfiguracionLiquidarDespacho = $em->getRepository(TteConfiguracion::class)->liquidarDespacho();
                                $arDespacho->setVehiculoRel($arVehiculo);
                                $arDespacho->setConductorRel($arConductor);
                                $descuentos = $arDespacho->getVrDescuentoPapeleria() + $arDespacho->getVrDescuentoSeguridad() + $arDespacho->getVrDescuentoCargue() + $arDespacho->getVrDescuentoEstampilla();
                                $retencionFuente = 0;
                                if($arDespacho->getVrFletePago() > $arrConfiguracionLiquidarDespacho['vrBaseRetencionFuente']) {
                                    $retencionFuente = $arDespacho->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeRetencionFuente'] / 100;
                                }
                                $industriaComercio = $arDespacho->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeIndustriaComercio'] /100;

                                $total = $arDespacho->getVrFletePago() - ($arDespacho->getVrAnticipo() + $retencionFuente + $industriaComercio);
                                $saldo = $total - $descuentos;
                                $totalNeto = $arDespacho->getVrFletePago() - ($arDespacho->getVrAnticipo() + $retencionFuente + $industriaComercio + $descuentos);
                                $arDespacho->setVrIndustriaComercio($industriaComercio);
                                $arDespacho->setVrRetencionFuente($retencionFuente);
                                $arDespacho->setVrTotal($total);
                                $arDespacho->setVrSaldo($saldo);
                                $arDespacho->setVrTotalNeto($totalNeto);
                                if ($id == 0) {
                                    $arDespacho->setFechaRegistro(new \DateTime('now'));
                                    $arDespacho->setFechaSalida(new \DateTime('now'));
                                    $arDespacho->setUsuario($this->getUser()->getUsername());
                                    $arDespacho->setOperacionRel($this->getUser()->getOperacionRel());
                                }
                                $em->persist($arDespacho);
                                $em->flush();
                                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $arDespacho->getCodigoDespachoPk())));
                            } else {
                                Mensajes::error('No se ha encontrado un vehiculo con el codigo ingresado');
                            }
                        } else {
                            Mensajes::error('Debe de seleccionar un vehiculo');
                        }
                    } else {
                        Mensajes::error('No se ha encontrado un conductor con el codigo ingresado');
                    }
                } else {
                    Mensajes::error('Debe seleccionar un coductor');
                }
            }
        }
        return $this->render('transporte/movimiento/transporte/despacho/nuevo.html.twig', ['arDespacho' => $arDespacho, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/transporte/movimiento/transporte/despacho/detalle/{id}", name="transporte_movimiento_transporte_despacho_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => true);
        $arrBotonRetirarGuia = array('label' => 'Retirar', 'disabled' => false);
        $arrBotonActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonRndc = array('label' => 'RNDC', 'disabled' => true);
        $arrBotonImprimirManifiesto = array('label' => 'Manifiesto', 'disabled' => false);
        $arrBotonCobroEntrega = array('label' => 'Cobro entrega', 'disabled' => true);
        $arrBotonLiquidacion = array('label' => 'Liquidacion', 'disabled' => true);
        if ($arDespacho->getEstadoAutorizado()) {
            $arrBotonRetirarGuia['disabled'] = true;
            $arrBotonActualizar['disabled'] = true;
            $arrBotonCobroEntrega['disabled'] = false;
        }
        if ($arDespacho->getEstadoAprobado()) {
            if (!$arDespacho->getEstadoAnulado()) {
                $arrBotonLiquidacion['disabled'] = false;
                $arrBotonCerrar['disabled'] = false;
                $arrBotonRndc['disabled'] = false;
                $arrBotonCobroEntrega['disabled'] = false;

                if ($arDespacho->getEstadoCerrado()) {
                    $arrBotonCerrar['disabled'] = true;
                }
            }
        } else {
            $arrBotonImprimirManifiesto['disabled'] = true;
        }

        $form = Estandares::botonera($arDespacho->getEstadoAutorizado(), $arDespacho->getEstadoAprobado(), $arDespacho->getEstadoAnulado());
        $form
            ->add('btnCerrar', SubmitType::class, $arrBotonCerrar)
            ->add('btnRndc', SubmitType::class, $arrBotonRndc)
            ->add('btnRetirarGuia', SubmitType::class, $arrBotonRetirarGuia)
            ->add('btnActualizar', SubmitType::class, $arrBotonActualizar)
            ->add('btnImprimirManifiesto', SubmitType::class, $arrBotonImprimirManifiesto)
            ->add('btnLiquidacion', SubmitType::class, $arrBotonLiquidacion)
            ->add('btnCobroEntrega', SubmitType::class, $arrBotonCobroEntrega)
            ->add('btnRetirarNovedad', SubmitType::class, array('label' => 'Retirar'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteDespacho::class)->autorizar($arDespacho);
                $em->getRepository(TteDespacho::class)->liquidar($arDespacho);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteDespacho::class)->desautorizar($arDespacho);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->aprobar($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnCerrar')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->cerrar($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnRndc')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->reportarRndc($arDespacho);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->anular($arDespacho);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrDespachoDetalles = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->retirarDetalle($arrDespachoDetalles);
                if ($respuesta) {
                    $this->getDoctrine()->getRepository(TteDespacho::class)->liquidar($id);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $this->getDoctrine()->getRepository(TteDespacho::class)->liquidar($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Despacho();
                $formato->Generar($em, $id);

            }
            if ($form->get('btnImprimirManifiesto')->isClicked()) {
                if($arDespacho->getDespachoTipoRel()->getViaje () != 1){
                    $formato = new RelacionEntrega();
                    $formato->Generar($em, $id);
                } else {
                    $formato = new Manifiesto();
                    $formato->Generar($em, $id);
                }
            }
            if ($form->get('btnCobroEntrega')->isClicked()) {
                $formato = new CobroEntrega();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnLiquidacion')->isClicked()) {
                $formato = new Liquidacion();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnRetirarNovedad')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteNovedad::class)->eliminar($arrSeleccionados);
            }
        }
        $arNovedades = $em->getRepository(TteNovedad::class)->despacho($id);
        $arDespachoDetalles = $em->getRepository(TteDespachoDetalle::class)->despacho($id);
        return $this->render('transporte/movimiento/transporte/despacho/detalle.html.twig', [
            'arDespacho' => $arDespacho,
            'arNovedades' => $arNovedades,
            'arDespachoDetalles' => $arDespachoDetalles,
            'clase' => array('clase'=>'tte_despacho', 'codigo' => $id),
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/movimiento/trasnporte/despacho/detalle/adicionar/guia/{id}", name="transporte_movimiento_transporte_despacho_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('txtNumero', TextType::class, ['required' => false,'data' => $session->get('filtroTteDespachoGuiaNumero')])
            ->add('cboGuiaTipoRel', EntityType::class, $em->getRepository(TteGuiaTipo::class)->llenarCombo())
            ->add('cboRutaRel', EntityType::class, $em->getRepository(TteRuta::class)->llenarCombo())
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                        if($arGuia) {
                            if($arGuia->getCodigoDespachoFk() == NULL) {
                                $arGuia->setDespachoRel($arDespacho);
                                $arGuia->setEstadoEmbarcado(1);
                                $em->persist($arGuia);
                                $arDespachoDetalle = new TteDespachoDetalle();
                                $arDespachoDetalle->setDespachoRel($arDespacho);
                                $arDespachoDetalle->setGuiaRel($arGuia);
                                $arDespachoDetalle->setVrDeclara($arGuia->getVrDeclara());
                                $arDespachoDetalle->setVrFlete($arGuia->getVrFlete());
                                $arDespachoDetalle->setVrManejo($arGuia->getVrManejo());
                                $arDespachoDetalle->setVrRecaudo($arGuia->getVrRecaudo());
                                $arDespachoDetalle->setVrCobroEntrega($arGuia->getVrCobroEntrega());
                                $arDespachoDetalle->setUnidades($arGuia->getUnidades());
                                $arDespachoDetalle->setPesoReal($arGuia->getPesoReal());
                                $arDespachoDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                                if($arGuia->getPesoReal() >= $arGuia->getPesoVolumen()) {
                                    $arDespachoDetalle->setPesoCosto($arGuia->getPesoReal());
                                } else {
                                    $arDespachoDetalle->setPesoCosto($arGuia->getPesoVolumen());
                                }
                                $em->persist($arDespachoDetalle);
                            }
                        }
                    }
                    $em->flush();
                    $em->getRepository(TteDespacho::class)->liquidar($id);
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteDespachoGuiaNumero', $form->get('txtNumero')->getData());

                if ($form->get('cboRutaRel')->getData() != '') {
                    $session->set('filtroTteDespachoGuiaCodigoRuta', $form->get('cboRutaRel')->getData()->getCodigoRutaPk());
                } else {
                    $session->set('filtroTteDespachoGuiaCodigoRuta', null);
                }
                if ($form->get('cboGuiaTipoRel')->getData() != '') {
                    $session->set('filtroTteDespachoGuiaCodigoGuiaTipo', $form->get('cboGuiaTipoRel')->getData()->getCodigoGuiaTipoPk());
                } else {
                    $session->set('filtroTteDespachoGuiaCodigoGuiaTipo', null);
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->despachoPendiente($arDespacho->getCodigoOperacionFk()), $request->query->getInt('page', 1), 300);
        return $this->render('transporte/movimiento/transporte/despacho/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $codigoDespacho
     * @param $id
     * @return Response
     * @Route("/transporte/movimiento/trasnporte/despacho/detalle/adicionar/novedad/{codigoDespacho}/{id}", name="transporte_movimiento_transporte_despacho_detalle_adicionar_novedad")
     */
    public function detalleAdicionarNovedad(Request $request, $codigoDespacho, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = new TteNovedad();
        if ($id == 0) {
            $arNovedad->setEstadoAtendido(true);
            $arNovedad->setFechaReporte(new \DateTime('now'));
            $arNovedad->setFecha(new \DateTime('now'));
        } else {
            $arNovedad = $em->getRepository(TteNovedad::class)->find($id);
        }
        $form = $this->createForm(NovedadType::class, $arNovedad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
            $arDespacho->setEstadoNovedad(true);
            $em->persist($arDespacho);

            $arNovedad->setDespachoRel($arDespacho);
            if ($id == 0) {
                $arNovedad->setFechaRegistro(new \DateTime('now'));
                $arNovedad->setFechaAtencion(new \DateTime('now'));
                $arNovedad->setFechaSolucion(new \DateTime('now'));
            }
            $em->persist($arNovedad);

            if($arNovedad->getAplicaGuia()) {
                $arGuias = $em->getRepository(TteGuia::class)->findBy(array('codigoDespachoFk' => $codigoDespacho));
                foreach ($arGuias as $arGuia) {
                    $arNovedadGuia = new TteNovedad();
                    $arNovedadGuia->setCodigoDespachoReferenciaFk($codigoDespacho);
                    $arNovedadGuia->setGuiaRel($arGuia);
                    $arNovedadGuia->setFechaRegistro(new \DateTime('now'));
                    $arNovedadGuia->setFechaAtencion(new \DateTime('now'));
                    $arNovedadGuia->setFechaSolucion(new \DateTime('now'));
                    $arNovedadGuia->setDescripcion($arNovedad->getDescripcion());
                    $arNovedadGuia->setFecha($arNovedad->getFecha());
                    $arNovedadGuia->setFechaReporte($arNovedad->getFechaReporte());
                    $arNovedadGuia->setNovedadTipoRel($arNovedad->getNovedadTipoRel());
                    $arNovedadGuia->setEstadoAtendido($arNovedad->getEstadoAtendido());
                    $em->persist($arNovedadGuia);
                    $arGuia->setEstadoNovedad(1);
                    $em->persist($arGuia);
                }
            }

            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('transporte/movimiento/transporte/despacho/detalleAdicionarNovedad.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/transporte/movimiento/transporte/despacho/liquidar/{id}", name="transporte_movimiento_transporte_despacho_liquidar")
     */
    public function liquidar(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = new TteDespacho();
        if ($id != 0) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        }
        $form = $this->createForm(DespachoLiquidarType::class, $arDespacho);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arDespacho = $form->getData();
                $em->getRepository(TteDespacho::class)->liquidar($id);
                $em->getRepository(TteDespacho::class)->costos($arDespacho);

                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('transporte/movimiento/transporte/despacho/liquidar.html.twig', ['arDespacho' => $arDespacho, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/novedad/despacho/solucion/{codigoNovedad}", name="transporte_movimiento_transporte_novedad_despacho_solucion")
     */
    public function novedadSolucion(Request $request, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TteNovedad::class)->find($codigoNovedad);
        $form = $this->createFormBuilder()
            ->add('aplicaSolucionGuias', CheckboxType::class, array('required' => false))
            ->add('solucion', TextareaType::class, array('label' => 'Solucion'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arNovedad->setEstadoSolucion(1);
            $arNovedad->setSolucion($form->get('solucion')->getData());
            $arNovedad->setFechaSolucion(new \DateTime('now'));
            $em->persist($arNovedad);

            $arDespacho = $em->getRepository(TteDespacho::class)->find($arNovedad->getCodigoDespachoFk());
            $arDespacho->setEstadoNovedad(0);
            $arDespacho->setEstadoNovedadSolucion(1);
            $em->persist($arDespacho);

            if($form->get('aplicaSolucionGuias')->getData()) {
                $arNovedades = $em->getRepository(TteNovedad::class)->findBy(array('codigoDespachoReferenciaFk' => $arNovedad->getCodigoDespachoFk(), 'estadoSolucion' => 0));
                foreach ($arNovedades as $arNovedadGuia) {
                    $arNovedadGuia->setEstadoSolucion(1);
                    $arNovedadGuia->setSolucion($form->get('solucion')->getData());
                    $arNovedadGuia->setFechaSolucion(new \DateTime('now'));
                    $em->persist($arNovedadGuia);

                    $arGuia = $em->getRepository(TteGuia::class)->find($arNovedadGuia->getGuiaRel()->getCodigoGuiaPk());
                    $arGuia->setEstadoNovedad(0);
                    $arGuia->setEstadoNovedadSolucion(1);
                    $em->persist($arGuia);
                }
            }

            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('transporte/movimiento/transporte/despacho/novedadSolucion.html.twig', array (
            'form' => $form->createView()));
    }

}