<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuNovedad;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Entity\RecursoHumano\RhuVacacionCambio;
use App\Form\Type\RecursoHumano\VacacionCambiosType;
use App\Form\Type\RecursoHumano\VacacionType;
use App\Formato\RecursoHumano\Vacaciones;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use function Complex\add;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VacacionesController extends AbstractController
{
    protected $clase = RhuVacacion::class;
    protected $claseFormulario = VacacionType::class;
    protected $claseNombre = "RhuVacacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Vacacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/vacacion/lista", name="recursohumano_movimiento_nomina_vacacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoGrupoFk', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.codigoGrupoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoVacacionPk', IntegerType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('numero', IntegerType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuVacacion::class)->lista($raw), "Vacaciones");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuVacacion::class)->eliminar($arrSeleccionados);

            }
        }
        $arVacaciones = $paginator->paginate($em->getRepository(RhuVacacion::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/vacacion/lista.html.twig', [
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/nomina/vacacion/nuevo/{id}", name="recursohumano_movimiento_nomina_vacacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new RhuVacacion();
        if ($id != 0) {
            $arVacacion = $em->getRepository($this->clase)->find($id);
        } else {
            $arVacacion->setFecha(new \DateTime('now'));
            $arVacacion->setFechaDesdeDisfrute(new \DateTime('now'));
            $arVacacion->setFechaHastaDisfrute(new \DateTime('now'));
            $arVacacion->setFechaInicioLabor(new \DateTime('now'));
        }
        $form = $this->createForm(VacacionType::class, $arVacacion);
        $form->handleRequest($request);
        if ($form->get('guardar')->isClicked()) {
            $txtCodigoEmpleado = $request->request->get('txtCodigoEmpleado');
            if ($txtCodigoEmpleado != '') {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($txtCodigoEmpleado);
                $arVacacion = $form->getData();
                $validarVacacion = true;
                if ($id == 0) {
                    $validarVacacion = $em->getRepository(RhuVacacion::class)->validarVacacion($arVacacion->getFechaDesdeDisfrute(), $arVacacion->getFechaDesdeDisfrute(), $arVacacion->getCodigoEmpleadoFk());
                }
                if ($validarVacacion) {
                    $boolNovedad = $em->getRepository(RhuNovedad::class)->validarFecha($arVacacion->getFechaDesdeDisfrute(), $arVacacion->getFechaHastaDisfrute(), $arVacacion->getCodigoEmpleadoFk());
                    if ($boolNovedad) {
                        if ($arEmpleado->getCodigoContratoFk() != '') {
                            if ($form->get('fechaDesdeDisfrute')->getData() > $form->get('fechaHastaDisfrute')->getData()) {
                                Mensajes::error("La fecha desde no debe ser mayor a la fecha hasta");
                            } else {
                                if ($form->get('diasDisfrutados')->getData() == 0 && $form->get('diasPagados')->getData() == 0) {
                                    Mensajes::error("Los dias pagados o los dias disfrutados, no pueden estar en ceros");
                                } else {
                                    $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                                    $arVacacion->setContratoRel($arContrato);
                                    // Calcular fecha desde periodo y hasta periodo
                                    $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
                                    if ($fechaDesdePeriodo == null) {
                                        $fechaDesdePeriodo = $arContrato->getFechaDesde();
                                    }
                                    $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;
                                    $fechaHastaPeriodo = $em->getRepository(RhuLiquidacion::class)->diasPrestacionesHasta($intDias + 1, $fechaDesdePeriodo);
                                    $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
                                    $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);
                                    // Calcular fecha desde periodo y hasta periodo

                                    $intDiasDevolver = $arVacacion->getDiasPagados();
                                    $diasDisfrutadosReales = 0;
                                    if ($arVacacion->getDiasDisfrutados() > 0) {
                                        $intDias = $arVacacion->getFechaDesdeDisfrute()->diff($arVacacion->getFechaHastaDisfrute());
                                        $intDias = $intDias->format('%a');
                                        $intDiasDevolver += $intDias + 1;
                                        $diasDisfrutadosReales = $intDias + 1;
                                    }
                                    $arVacacion->setDias($intDiasDevolver);
                                    $arVacacion->setDiasDisfrutadosReales($diasDisfrutadosReales);
                                    $arVacacion->setEmpleadoRel($arEmpleado);
                                    $arVacacion->setGrupoRel($arContrato->getGrupoRel());
                                    $em->persist($arVacacion);

                                    //Calcular deducciones credito
                                    if ($id == 0) {
                                        $floVrDeducciones = 0;
                                        $arCreditos = $em->getRepository(RhuCredito::class)->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'codigoCreditoPagoTipoFk' => 'NOM', 'estadoPagado' => 0, 'estadoSuspendido' => 0));
                                        foreach ($arCreditos as $arCredito) {
                                            $arVacacionAdicional = new RhuVacacionAdicional();
                                            $arVacacionAdicional->setCreditoRel($arCredito);
                                            $arVacacionAdicional->setVacacionRel($arVacacion);
                                            $arVacacionAdicional->setVrDeduccion($arCredito->getVrCuota());
                                            $arVacacionAdicional->setConceptoRel($arCredito->getCreditoTipoRel()->getConceptoRel());
                                            $em->persist($arVacacionAdicional);
                                            $floVrDeducciones += $arCredito->getVrCuota();
                                        }
                                    }

                                    // calcular adicionales al pago permanentes
                                    if ($id == 0) {
                                        $arAdicionales = $em->getRepository(RhuAdicional::class)->findBy(['codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'permanente' => 1, 'estadoInactivo' => 0]);
                                        foreach ($arAdicionales as $arAdicional) {
                                            $arVacacionAdicional = new RhuVacacionAdicional();
                                            $arVacacionAdicional->setVacacionRel($arVacacion);
                                            $arVacacionAdicional->setConceptoRel($arAdicional->getConceptoRel());
                                            $arVacacionAdicional->setVrDeduccion($arAdicional->getVrValor());
                                            $em->persist($arVacacionAdicional);
                                        }
                                    }

                                    $em->flush();
                                    $em->getRepository(RhuVacacion::class)->liquidar($arVacacion->getCodigoVacacionPk());

                                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_vacacion_detalle', ['id' => $arVacacion->getCodigoVacacionPk()]));
                                }
                            }
                        } else {
                            Mensajes::error("error", "El empleado no tiene contrato activo");
                        }
                    } else {
                        Mensajes::error("El empleado tiene una novedad registrada en este periodo.");
                    }
                } else {
                    Mensajes::error("El empleado tiene una vacación registrada en este periodo.");
                }
            } else {
                Mensajes::error("Debe seleccionar un empleado");
            }
        }
        return $this->render('recursohumano/movimiento/nomina/vacacion/nuevo.html.twig', [
            'form' => $form->createView(),
            'arVacacion' => $arVacacion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("recursohumano/movimiento/nomina/vacacion/detalle/{id}", name="recursohumano_movimiento_nomina_vacacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVacacion = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arVacacion->getEstadoAutorizado(), $arVacacion->getEstadoAprobado(), $arVacacion->getEstadoAnulado());
        $form->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formatoVacaciones = new Vacaciones();
                $formatoVacaciones->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(RhuVacacion::class)->autorizar($arVacacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_vacacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuVacacion::class)->desautorizar($arVacacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_vacacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(RhuVacacion::class)->aprobar($arVacacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_vacacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arVacacionAdicional = $em->getRepository(RhuVacacionAdicional::class)->find($codigo);
                        if ($arVacacionAdicional) {
                            $em->remove($arVacacionAdicional);
                        }
                    }
                    $em->flush();
                }
                $em->getRepository(RhuVacacion::class)->liquidar($id);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_vacacion_detalle', ['id' => $id]));
            }
        }
        $arVacacionAdicionales = $em->getRepository(RhuVacacionAdicional::class)->findBy(['codigoVacacionFk' => $id]);
        $arVacacionCambios = $em->getRepository(RhuVacacionCambio::class)->findBy(['codigoVacacionFk' => $id]);
        return $this->render('recursohumano/movimiento/nomina/vacacion/detalle.html.twig', [
            'arVacacion' => $arVacacion,
            'arVacacionAdicionales' => $arVacacionAdicionales,
            'arVacacionCambios' => $arVacacionCambios,
            'clase' => array('clase' => 'RhuVacacion', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/vacacion/detalle/credito/{codigoVacacion}", name="recursohumano_movimiento_nomina_vacacion_detalle_credito")
     */
    public function detalleCredito(Request $request, $codigoVacacion)
    {

        $em = $this->getDoctrine()->getManager();
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);
        $arCreditos = $em->getRepository(RhuCredito::class)->pendientes($arVacacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCredito = $em->getRepository(RhuCredito::class)->find($codigoCredito);
                        $valor = 0;
                        if ($arrControles['TxtValor' . $codigoCredito] != '') {
                            $valor = $arrControles['TxtValor' . $codigoCredito];
                        }
                        $arVacacionAdicional = new RhuVacacionAdicional();
                        $arVacacionAdicional->setCreditoRel($arCredito);
                        $arVacacionAdicional->setVacacionRel($arVacacion);
                        $arVacacionAdicional->setVrDeduccion($valor);
                        $arVacacionAdicional->setConceptoRel($arCredito->getCreditoTipoRel()->getConceptoRel());
                        $em->persist($arVacacionAdicional);
                        $floVrDeducciones += $valor;
                    }
                    $em->flush();
                    $em->getRepository(RhuVacacion::class)->liquidar($arVacacion->getCodigoVacacionPk());
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('recursohumano/movimiento/nomina/vacacion/detalleNuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/vacacion/detalle/descuento/{codigoVacacion}", name="recursohumano_movimiento_nomina_vacacion_detalle_descuento")
     */
    public function detalleDescuento(Request $request, $codigoVacacion)
    {

        $em = $this->getDoctrine()->getManager();
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);
        $form = $this->createFormBuilder()
            ->add('conceptoRel', EntityType::class, array(
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                        ->where("pc.adicionalTipo = 'DES'")
                        ->orderBy('pc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('txtValor', NumberType::class, array('required' => true))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arPagoConcepto = new RhuConcepto();
                $arPagoConcepto = $form->get('conceptoRel')->getData();
                $arVacacionAdicional = new RhuVacacionAdicional();
                $arVacacionAdicional->setVacacionRel($arVacacion);
                $arVacacionAdicional->setConceptoRel($arPagoConcepto);
                $arVacacionAdicional->setVrDeduccion($form->get('txtValor')->getData());
                $em->persist($arVacacionAdicional);
                $em->flush();
                $em->getRepository(RhuVacacion::class)->liquidar($arVacacion->getCodigoVacacionPk());
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/nomina/vacacion/detalleDescuentoNuevo.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/vacacion/detalle/bonificacion/{codigoVacacion}", name="recursohumano_movimiento_nomina_vacacion_detalle_bonificacion")
     */
    public function detalleBonificacion(Request $request, $codigoVacacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);
        $form = $this->createFormBuilder()
            ->add('conceptoRel', EntityType::class, array(
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                        ->where("pc.adicionalTipo = 'DES'")
                        ->orderBy('pc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('txtValor', NumberType::class, array('required' => true))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arPagoConcepto = new RhuConcepto();
                $arPagoConcepto = $form->get('conceptoRel')->getData();
                $arVacacionAdicional = new RhuVacacionAdicional();
                $arVacacionAdicional->setVacacionRel($arVacacion);
                $arVacacionAdicional->setConceptoRel($arPagoConcepto);
                $arVacacionAdicional->setVrBonificacion($form->get('txtValor')->getData());
                $em->persist($arVacacionAdicional);
                $em->flush();
                $em->getRepository(RhuVacacion::class)->liquidar($arVacacion->getCodigoVacacionPk());
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/nomina/vacacion/detalleBonificacionNuevo.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/vacacion/detalle/cambio/{id}/{codigoVacacion}", name="recursohumano_movimiento_nomina_vacacion_detalle_vacacion_cambio")
     */
    public function detalleVacacionesCambio(Request $request, $id, $codigoVacacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);

        $arVacacionesCambio = new RhuVacacionCambio();

        if ($id != 0) {
            $arVacacionesCambio = $em->getRepository(RhuVacacionCambio::class)->find($id);
        } else {
            $arVacacionesCambio->setFechaDesdeDisfrute($arVacacion->getFechaDesdeDisfrute());
            $arVacacionesCambio->setFechaHastaDisfrute($arVacacion->getFechaHastaDisfrute());
            $arVacacionesCambio->setFechaInicioLabor($arVacacion->getFechaInicioLabor());
            $arVacacionesCambio->setCodigoUsuario($this->getUser()->getUsername());
            $arVacacionesCambio->setVacacionRel($arVacacion);
        }
        $form = $this->createForm(VacacionCambiosType::class, $arVacacionesCambio);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $boolIncapacidad = $em->getRepository(RhuIncapacidad::class)->validarFecha($arVacacionesCambio->getFechaDesdeDisfrute(), $arVacacionesCambio->getFechaHastaDisfrute(), $arVacacion->getCodigoEmpleadoFk());
                if ($boolIncapacidad) {
                    $arUltimaVacacionDisfrute = $em->getRepository(RhuVacacionCambio::class)->validarUltimaVacacion($codigoVacacion);
                    if ($arUltimaVacacionDisfrute) {
                        $arVacacion->setFechaDesdeDisfrute($arVacacionesCambio->getFechaDesdeDisfrute());
                        $arVacacion->setFechaHastaDisfrute($arVacacionesCambio->getFechaHastaDisfrute());
                        $arVacacion->setFechaInicioLabor($arVacacionesCambio->getFechaInicioLabor());
                    }
                    $arVacacionesCambio = $form->getData();
                    $em->persist($arVacacionesCambio);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    Mensajes::error("El empleado tiene una incapacidad registrada en este periodo.");
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/vacacion/vacacionesCambio.html.twig', [
            'form' => $form->createView(),
            'arVacacion' => $arVacacion
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoVacacion' => $form->get('codigoVacacionPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arGrupo = $form->get('codigoGrupoFk')->getData();

        if (is_object($arGrupo)) {
            $filtro['grupo'] = $arGrupo->getCodigoGrupoPk();
        } else {
            $filtro['grupo'] = $arGrupo;
        }

        return $filtro;

    }
}

