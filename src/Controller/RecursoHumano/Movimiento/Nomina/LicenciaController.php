<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuLicenciaTipo;
use App\Form\Type\RecursoHumano\LicenciaType;
use App\General\General;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class LicenciaController extends AbstractController
{
    protected $clase = RhuLicencia::class;
    protected $claseNombre = "RhuLicencia";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Licencia";
    protected $nombre = "Licencia";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/moviento/nomina/licencia/lista", name="recursohumano_movimiento_nomina_licencia_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('codigoGrupoFk', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoLicenciaTipoFk', EntityType::class, [
                'class' => RhuLicenciaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.codigoLicenciaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoLicenciaPk', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
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
                General::get()->setExportar($em->getRepository(RhuLicencia::class)->lista($raw), "Licencias");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arLicenicasSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuLicencia::class)->eliminar($arLicenicasSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        }
        $arLicencias = $paginator->paginate($em->getRepository(RhuLicencia::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/licencia/lista.html.twig', [
            'arLicencias' => $arLicencias,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/licencia/nuevo/{id}", name="recursohumano_movimiento_nomina_licencia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLicencia = new RhuLicencia();
        if ($id != 0) {
            $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);

            if (!$arLicencia) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        } else {
            $arLicencia->setFechaDesde(new \DateTime('now'));
            $arLicencia->setFechaHasta(new \DateTime('now'));
            $arLicencia->setFechaAplicacion(new \DateTime('now'));
        }
        $form = $this->createForm(LicenciaType::class, $arLicencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $codigoEmpleado = $form->get('codigoEmpleadoFk')->getData();
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->findOneBy(['codigoEmpleadoPk' => $codigoEmpleado]);
                $arContrato = null;
                if ($arEmpleado->getCodigoContratoFk()) {
                    $arContrato = $arEmpleado->getContratoRel();
                } elseif ($arEmpleado->getCodigoContratoUltimoFk()) {
                    $arContrato = $em->getReference(RhuContrato::class, $arEmpleado->getCodigoContratoUltimoFk());
                }                $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
                $arLicencia = $form->getData();
                if ($arEmpleado) {
                    if ($arContrato) {
                        if ($arLicencia->getFechaDesde() <= $arLicencia->getFechaHasta()) {
                            if ($em->getRepository(RhuIncapacidad::class)->validarFecha($arLicencia->getFechaDesde(), $arLicencia->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), "")) {
                                if ($em->getRepository(RhuLicencia::class)->validarFecha($arLicencia->getFechaDesde(), $arLicencia->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arLicencia->getCodigoLicenciaPk())) {
                                    if ($arContrato->getFechaDesde() <= $arLicencia->getFechaDesde()) {
                                        $intDias = $arLicencia->getFechaDesde()->diff($arLicencia->getFechaHasta());
                                        $intDias = $intDias->format('%a');
                                        $intDias = $intDias + 1;
                                        $intDiasCobro = $intDias;
                                        $douPorcentajePago = $arLicencia->getLicenciaTipoRel()->getConceptoRel()->getPorcentaje();
                                        $arLicencia->setPorcentajePago($douPorcentajePago);
                                        $arLicencia->setFecha(new \DateTime('now'));
                                        $arLicencia->setEmpleadoRel($arEmpleado);
                                        $arLicencia->setGrupoRel($arContrato->getGrupoRel());
                                        $arLicencia->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                                        $arLicencia->setCodigoUsuario($this->getUser()->getUserName());
                                        $arLicencia->setDiasCobro($intDiasCobro);
                                        $arLicencia->setCantidad($intDias);
                                        $arLicencia->setMaternidad($arLicencia->getLicenciaTipoRel()->getMaternidad());
                                        $arLicencia->setPaternidad($arLicencia->getLicenciaTipoRel()->getPaternidad());
                                        $floVrLicencia = 0;
                                        $douVrDia = $arContrato->getVrSalario() / 30;
                                        $salarioEmpleado = $douVrDia * 30;
                                        $douVrDiaSalarioMinimo = $arContrato->getVrSalario() / 30;
                                        $arLicencia->setPorcentajePago($douPorcentajePago);
                                        if ($arLicencia->getVrIbcPropuesto() > 0) {
                                            $arrIbc = array('respuesta' => true, 'ibc' => $arLicencia->getVrIbcPropuesto(), 'dias' => 30);
                                        } else {
                                            $arrIbc = array('respuesta' => true, 'ibc' => $arContrato->getVrSalario(), 'dias' => 30);
                                        }
                                        if ($arLicencia->getLicenciaTipoRel()->getMaternidad() ||
                                            $arLicencia->getLicenciaTipoRel()->getPaternidad() ||
                                            $arLicencia->getLicenciaTipoRel()->getRemunerada()) {

                                            if ($arConfiguracion->getLiquidarLicenciasIbcMesAnterior() && (($arLicencia->getLicenciaTipoRel()->getMaternidad() || $arLicencia->getLicenciaTipoRel()->getPaternidad()))) {
                                                $arrIbc = $em->getRepository(RhuAporteDetalle::class)->ibcMesAnterior($arLicencia->getFechaDesde()->format('Y'), $arLicencia->getFechaDesde()->format('m'), $arEmpleado->getCodigoEmpleadoPk());
                                                if ($arLicencia->getVrIbcPropuesto() > 0) {
                                                    $arrIbc = array('respuesta' => true, 'ibc' => $arLicencia->getVrIbcPropuesto(), 'dias' => 30);
                                                }
                                                if (!$arrIbc['respuesta']) {
                                                    $arLicenciaAnterior = $em->getRepository(RhuLicencia::class)->findOneBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'codigoLicenciaTipoFk' => $arLicencia->getLicenciaTipoRel()->getCodigoLicenciaTipoPk()));
                                                    if ($arLicenciaAnterior) {
                                                        if (($arLicenciaAnterior->getDiasCobro() <= 31 && $arLicencia->getLicenciaTipoRel()->getMaternidad() == $arLicenciaAnterior->getMaternidad()) || ($arLicenciaAnterior->getDiasCobro() <= 8 && $arLicencia->getLicenciaTipoRel()->getPaternidad == $arLicenciaAnterior->getPaternidad()))
                                                            $arrIbc = array('respuesta' => true, 'ibc' => $arLicenciaAnterior->getVrIbcMesAnterior(), 'dias' => $arLicenciaAnterior->getDiasIbcMesAnterior());
                                                    }
                                                }
                                                $arLicencia->setVrIbcMesAnterior($arrIbc['ibc']);
                                                $arLicencia->setDiasIbcMesAnterior($arrIbc['dias']);
                                                if ($arrIbc['ibc'] != 0 && $arrIbc['dias'] != 0) {
                                                    $douVrDia = $arrIbc['ibc'] / $arrIbc['dias'];
                                                }
                                                $salarioEmpleado = $douVrDia * 30;
                                            }
                                            if ($arConfiguracion->getLiquidarLicenciasIbcMesAnterior() && (($arLicencia->getLicenciaTipoRel()->getRemunerada()))) {
                                                $arrIbc = $em->getRepository(RhuAporteDetalle::class)->ibcMesAnterior($arLicencia->getFechaDesde()->format('Y'), $arLicencia->getFechaDesde()->format('m'), $arEmpleado->getCodigoEmpleadoPk());
                                                $arLicencia->setVrIbcMesAnterior($arrIbc['ibc']);
                                                $arLicencia->setDiasIbcMesAnterior($arrIbc['dias']);
                                            }
                                        }
                                        if ($salarioEmpleado <= $arContrato->getVrSalario()) {
                                            $floVrLicencia = $intDiasCobro * $douVrDiaSalarioMinimo;
                                        } else {
                                            if ($arEmpleado->getContratoRel()->getVrSalario()) {
                                                $floVrLicencia = $intDiasCobro * $douVrDia;
                                            }
                                        }
                                        $arLicencia->setVrCobro(round($floVrLicencia));
                                        if ($arLicencia->getVrPropuesto() > 0) {
                                            $floVrLicencia = $arLicencia->getVrPropuesto();
                                        }
                                        $arLicencia->setVrCobro(0);
                                        $arLicencia->setVrLicencia(round($floVrLicencia));
                                        $arLicencia->setVrSaldo(round($floVrLicencia));
                                        if ($arEmpleado->getCodigoContratoFk() != '') {
                                            $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                                        } else {
                                            $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoUltimoFk());
                                        }
                                        $arLicencia->setContratoRel($arContrato);
                                        $em->persist($arLicencia);
                                        $em->flush();
                                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_detalle', array("id" => $arLicencia->getCodigoLicenciaPk())));
                                    }else{
                                        Mensajes::error("La fecha de inicio del contrato es mayor a la licencia");
                                    }
                                } else {
                                    Mensajes::error("Existe otra licencia en este rango de fechas");
                                }
                            } else {
                                Mensajes::error("Hay incapacidades que se cruzan con la fecha de la licencia");
                            }
                        } else {
                            Mensajes::error("La fecha desde debe ser inferior o igual a la fecha hasta");
                        }
                    } else {
                        Mensajes::error("El contrato no existe");
                    }
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista', ['id' => $arLicencia->getCodigoLicenciaPk()]));
                }  else {
                    Mensajes::error("El empleado no existe");
                }

            }
        }
        return $this->render('recursohumano/movimiento/nomina/licencia/nuevo.html.twig', [
            'form' => $form->createView(),
            'arIncapacidad' => $arLicencia,
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/licencia/detalle/{id}", name="recursohumano_movimiento_nomina_licencia_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);
            if (!$arLicencia) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        }
        $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);
        return $this->render('recursohumano/movimiento/nomina/licencia/detalle.html.twig', [
            'arLicencia' => $arLicencia
        ]);

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoLicencia' => $form->get('codigoLicenciaPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
        ];

        $arLicenciaTipo = $form->get('codigoLicenciaTipoFk')->getData();
        $arGrupo = $form->get('codigoGrupoFk')->getData();

        if (is_object($arLicenciaTipo)) {
            $filtro['licenciaTipo'] = $arLicenciaTipo->getCodigoLicenciaTipoPK();
        } else {
            $filtro['licenciaTipo'] = $arLicenciaTipo;
        }

        if (is_object($arGrupo)) {
            $filtro['grupo'] = $arGrupo->getCodigoGrupoPk();
        } else {
            $filtro['grupo'] = $arGrupo;
        }

        return $filtro;

    }

}