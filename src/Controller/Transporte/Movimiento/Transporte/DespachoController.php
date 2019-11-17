<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenProceso;
use App\Entity\Seguridad\SegUsuarioProceso;
use App\Entity\Transporte\TteAuxiliar;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoAuxiliar;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteMonitoreoRegistro;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TtePoseedor;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteUbicacion;
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
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

class DespachoController extends AbstractController
{
    protected $clase = TteDespacho::class;
    protected $proceso = "0006";
    protected $procestoTipo = "P";
    protected $nombreProceso = "Generar RNDC";
    protected $claseNombre = "TteDespacho";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Despacho";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/transporte/despacho/lista", name="transporte_movimiento_transporte_despacho_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoConductorFk', TextType::class, array('required' => false))
            ->add('codigoDespachoPk', TextType::class, array('required' => false))
            ->add('codigoVehiculoFk', TextType::class, array('required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoCiudadOrigenFk', TextType::class, array('required' => false))
            ->add('codigoCiudadDestinoFk', TextType::class, array('required' => false))
            ->add('codigoDespachoTipoFk', EntityType::class, [
                'class' => TteDespachoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('dt')
                        ->orderBy('dt.codigoDespachoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoOperacionFk', EntityType::class, [
                'class' => TteOperacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.codigoOperacionPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaSalidaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaSalidaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoSoporte', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltro($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltro($form);
                General::get()->setExportar($em->getRepository(TteDespacho::class)->lista($raw), "Despachos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TteDespacho::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_lista'));
            }
        }
        $arDespachos = $paginator->paginate($em->getRepository(TteDespacho::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('transporte/movimiento/transporte/despacho/lista.html.twig', [
            'arDespachos'=>$arDespachos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Exception
     * @Route("/transporte/movimiento/transporte/despacho/nuevo/{clase}/{id}", name="transporte_movimiento_transporte_despacho_nuevo")
     */
    public function nuevo(Request $request, $clase, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = new TteDespacho();
        if ($id != 0) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        }else {
            $arDespacho->setFechaRegistro(new \DateTime('now'));
            $arDespacho->setFechaSalida(new \DateTime('now'));
            $arDespacho->setUsuario($this->getUser()->getUsername());
            $arDespacho->setOperacionRel($this->getUser()->getOperacionRel());
            $arDespacho->setCodigoDespachoClaseFk($clase);
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
                                $arDespacho->setVehiculoRel($arVehiculo);
                                $arDespacho->setPoseedorRel($arVehiculo->getPoseedorRel());
                                $arDespacho->setConductorRel($arConductor);
                                if(!$arVehiculo->getPropio()) {
                                    $arDespacho->setVrCostoPago($arDespacho->getVrFletePago());
                                }
                                $em->persist($arDespacho);
                                $em->getRepository(TteDespacho::class)->liquidar($arDespacho);
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
        $arrBotonEliminarGuia = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonEliminarAuxiliar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonRndc = array('label' => 'RNDC', 'disabled' => true);
        $arrBotonImprimirManifiesto = array('label' => 'Manifiesto', 'disabled' => false);
        $arrBotonCobroEntrega = array('label' => 'Cobro entrega', 'disabled' => true);
        $arrBotonLiquidacion = array('label' => 'Liquidacion', 'disabled' => true);
        if ($arDespacho->getEstadoAutorizado()) {
            $arrBotonEliminarAuxiliar['disabled'] = true;
            $arrBotonEliminarGuia['disabled'] = true;
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
            ->add('btnEliminarGuia', SubmitType::class, $arrBotonEliminarGuia)
            ->add('btnActualizar', SubmitType::class, $arrBotonActualizar)
            ->add('btnImprimirManifiesto', SubmitType::class, $arrBotonImprimirManifiesto)
            ->add('btnLiquidacion', SubmitType::class, $arrBotonLiquidacion)
            ->add('btnCobroEntrega', SubmitType::class, $arrBotonCobroEntrega)
            ->add('btnEliminarNovedad', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnEliminarAuxiliar', SubmitType::class, $arrBotonEliminarAuxiliar);
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
                $this->getDoctrine()->getRepository(TteDespacho::class)->anular($arDespacho);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnEliminarGuia')->isClicked()) {
                $arrDespachoDetalles = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->retirarDetalle($arrDespachoDetalles);
                if ($respuesta) {
                    $this->getDoctrine()->getRepository(TteDespacho::class)->liquidar($arDespacho);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $this->getDoctrine()->getRepository(TteDespacho::class)->liquidar($arDespacho);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $id)));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Despacho();
                $formato->Generar($em, $id);

            }
            if ($form->get('btnImprimirManifiesto')->isClicked()) {
                if ($arDespacho->getDespachoTipoRel()->getViaje() != 1) {
                    $formato = new RelacionEntrega();
                    $formato->Generar($em, $id);
                } else {
                    if (!$em->find(GenConfiguracion::class, 1)->getCiudadRel()) {
                        Mensajes::error('Debe ingresar una ciudad en la configuracion general del sistema');
                    } else {
                        $formato = new Manifiesto();
                        $formato->Generar($em, $id);
                    }
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
            if ($form->get('btnEliminarNovedad')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteNovedad::class)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnEliminarAuxiliar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteDespachoAuxiliar::class)->eliminar($arrSeleccionados);
            }
        }
        $arNovedades = $em->getRepository(TteNovedad::class)->despacho($id);
        $arDespachoDetalles = $em->getRepository(TteDespachoDetalle::class)->despacho($id);
        $arAuxilares = $em->getRepository(TteDespachoAuxiliar::class)->despacho($id);
        return $this->render('transporte/movimiento/transporte/despacho/detalle.html.twig', [
            'arDespacho' => $arDespacho,
            'arNovedades' => $arNovedades,
            'arAuxiliares' => $arAuxilares,
            'arDespachoDetalles' => $arDespachoDetalles,
            'clase' => array('clase' => 'TteDespacho', 'codigo' => $id),
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/movimiento/trasnporte/despacho/detalle/adicionar/guia/{id}", name="transporte_movimiento_transporte_despacho_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroTteDespachoGuiaNumero')])
            ->add('verDireccion', CheckboxType::class, ['required' => false])
            ->add('cboCiudadDestinoRel', EntityType::class, $em->getRepository(TteCiudad::class)->llenarCombo("destino"))
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
                        if ($arGuia) {
                            if ($arGuia->getCodigoDespachoFk() == NULL) {
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
                                if ($arGuia->getPesoReal() >= $arGuia->getPesoVolumen()) {
                                    $arDespachoDetalle->setPesoCosto($arGuia->getPesoReal());
                                } else {
                                    $arDespachoDetalle->setPesoCosto($arGuia->getPesoVolumen());
                                }
                                $em->persist($arDespachoDetalle);
                            }
                        }
                    }
                    $em->flush();
                    $em->getRepository(TteDespacho::class)->liquidar($arDespacho);
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
                if ($form->get('cboCiudadDestinoRel')->getData() != '') {
                    $session->set('filtroTteDespachoCodigoCiudadDestino', $form->get('cboCiudadDestinoRel')->getData()->getCodigoCiudadPk());
                } else {
                    $session->set('filtroTteDespachoCodigoCiudadDestino', null);
                }
                if ($form->get('cboGuiaTipoRel')->getData() != '') {
                    $session->set('filtroTteDespachoGuiaCodigoGuiaTipo', $form->get('cboGuiaTipoRel')->getData()->getCodigoGuiaTipoPk());
                } else {
                    $session->set('filtroTteDespachoGuiaCodigoGuiaTipo', null);
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->despachoPendiente($arDespacho->getCodigoOperacionFk()), $request->query->getInt('page', 1), 300);
        return $this->render('transporte/movimiento/transporte/despacho/detalleAdicionarGuia.html.twig', [
            'arGuias' => $arGuias,
            'verDireccion' => $form->get('verDireccion')->getData(),
            'form' => $form->createView()]);
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

            if ($arNovedad->getAplicaGuia()) {
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
     * @param $codigoDespacho
     * @param $id
     * @return Response
     * @Route("/transporte/movimiento/trasnporte/despacho/detalle/adicionar/auxiliar/{id}", name="transporte_movimiento_transporte_despacho_detalle_adicionar_auxiliar")
     */
    public function detalleAdicionarAuxiliar(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('txtAuxiliar', TextType::class, ['required' => false, 'data' => $session->get('filtroTteDespachoAuxiliar')])
            ->add('txtIdentificacionAuxiliar', TextType::class, ['required' => false, 'data' => $session->get('filtroTteDespachoAuxiliarIdentificacion')])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arAuxiliar = $em->getRepository(TteAuxiliar::class)->find($codigo);
                        $arDespachoAuxiliar = new TteDespachoAuxiliar();
                        $arDespachoAuxiliar->setDespachoRel($arDespacho);
                        $arDespachoAuxiliar->setAuxiliarRel($arAuxiliar);
                        $em->persist($arDespachoAuxiliar);
                    }
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteDespachoAuxiliar', $form->get('txtAuxiliar')->getData());
                $session->set('filtroTteDespachoAuxiliarIdentificacion', $form->get('txtIdentificacionAuxiliar')->getData());
            }
        }
        $arAuxiliares = $paginator->paginate($em->getRepository(TteAuxiliar::class)->lista(), $request->query->getInt('page', 1), 300);
        return $this->render('transporte/movimiento/transporte/despacho/detalleAdicionarAuxiliar.html.twig', ['arAuxiliares' => $arAuxiliares, 'form' => $form->createView()]);
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
                $em->getRepository(TteDespacho::class)->liquidar($arDespacho);
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

            if ($form->get('aplicaSolucionGuias')->getData()) {
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
        return $this->render('transporte/movimiento/transporte/despacho/novedadSolucion.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/transporte/movimiento/transporte/despacho/mapa/{codigoDespacho}", name="transporte_movimiento_transporte_despacho_mapa")
     */
    public function verMapa(Request $request, $codigoDespacho)
    {
        $em = $this->getDoctrine()->getManager();
        //$googleMapsApiKey = $arConfiguracion->getGoogleMapsApiKey();
        //$googleMapsApiKey = "AIzaSyBXwGxeTtvba8Uset2XFjuwAxdRmJlkdcY";
        //Esta es la key de alejandro
        $googleMapsApiKey = "AIzaSyBXwGxeTtvba8Uset2XFjuwAxdRmJlkdcY";
        $arrDatos = $em->getRepository(TteUbicacion::class)->datosMapa($codigoDespacho);

        return $this->render('transporte/movimiento/transporte/despacho/mapaRegistro.html.twig', [
            'datos' => $arrDatos ?? [],
            'apikey' => $googleMapsApiKey]);
    }

    public function getFiltro($form){

         $filtro = [
            'codigoConductorFk' => $form->get('codigoConductorFk')->getData(),
            'codigoDespachoPk' => $form->get('codigoDespachoPk')->getData(),
            'codigoVehiculoFk' => $form->get('codigoVehiculoFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoCiudadOrigenFk' => $form->get('codigoCiudadOrigenFk')->getData(),
            'codigoCiudadDestinoFk' => $form->get('codigoCiudadDestinoFk')->getData(),
            'fechaSalidaDesde' => $form->get('fechaSalidaDesde')->getData() ?$form->get('fechaSalidaDesde')->getData()->format('Y-m-d'): null,
            'fechaSalidaHasta' => $form->get('fechaSalidaHasta')->getData() ?$form->get('fechaSalidaHasta')->getData()->format('Y-m-d'): null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoSoporte' => $form->get('estadoSoporte')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];
        $arDespachoTipo = $form->get('codigoDespachoTipoFk')->getData();
        $arOperacion = $form->get('codigoOperacionFk')->getData();

        if (is_object($arDespachoTipo)) {
            $filtro['codigoDespachoTipo'] = $arDespachoTipo->getCodigoDespachoTipoPk();
        } else {
            $filtro['codigoDespachoTipo'] = $arDespachoTipo;
        }

        if (is_object($arOperacion)) {
            $filtro['codigoOperacionFk'] = $arOperacion->getCodigoOperacionPk();
        } else {
            $filtro['codigoOperacionFk'] = $arOperacion;
        }

        return $filtro;
    }
}