<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina\Licencia;


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
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class LicenciaController extends ControllerListenerGeneral
{
    protected $clase = RhuLicencia::class;
    protected $claseNombre = "RhuLicencia";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Licencia";
    protected $nombre = "Licencia";

    /**
     * @Route("recursohumano/moviento/nomina/licencia/lista", name="recursohumano_movimiento_nomina_licencia_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoPk', TextType::class, array('required' => false))
            ->add('grupoRel', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('licenciaTipo', EntityType::class, [
                'class' => RhuLicenciaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.codigoLicenciaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('licenciaFechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuLicenciaFechaDesde') ? date_create($session->get('filtroRhuLicenciaFechaDesde')) : null])
            ->add('licenciafechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuLicenciaFechaHasta') ? date_create($session->get('filtroRhuLicenciaFechaHasta')) : null])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $codigoEmpleado = $form->get('codigoEmpleadoPk')->getData();
                $arGrupo = $form->get('grupoRel')->getData();
                $arLicenciaTipo = $form->get('licenciaTipo')->getData();
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado ?? 0);
                if ($arEmpleado) {
                    $session->set('filtroRhuLicenciaCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
                } else {
                    $session->set('filtroRhuLicenciaCodigoEmpleado', null);
                }
                if ($arLicenciaTipo) {
                    $session->set('filtroRhuLicenciaLiencenciaTipo', $arLicenciaTipo->getCodigoLicenciaTipoPk());
                } else {
                    $session->set('filtroRhuLicenciaLiencenciaTipo', null);
                }
                if ($arGrupo) {
                    $session->set('filtroRhuLicenciaCodigoGrupo', $arGrupo->getCodigoGrupoPk());
                } else {
                    $session->set('filtroRhuLicenciaCodigoGrupo', null);
                }
                $session->set('filtroRhuLicenciaFechaDesde', $form->get('licenciaFechaDesde')->getData() ? $form->get('licenciaFechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroRhuLicenciaFechaHasta', $form->get('licenciafechaHasta')->getData() ? $form->get('licenciafechaHasta')->getData()->format('Y-m-d') : null);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arLicenicasSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuLicencia::class, $arLicenicasSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        }

        $arLicencias = $paginator->paginate($em->getRepository(RhuLicencia::class)->lista(), $request->query->getInt('page', 1), 30);
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
        }
        $form = $this->createForm(LicenciaType::class, $arLicencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $codigoEmpleado = $form->get('codigoEmpleadoFk')->getData();
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->findOneBy(['codigoEmpleadoPk' => $codigoEmpleado]);
                $arContrato = $em->getRepository(RhuContrato::class)->findOneBy(['codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk() ?? 0]);
                $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
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
                                                    $arLicenciaAnterior = $em->getRepository( RhuLicencia::class)->findOneBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'codigoLicenciaTipoFk' => $arLicencia->getLicenciaTipoRel()->getCodigoLicenciaTipoPk()));
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
                                            if ($arContrato->getLiquidarLicenciasIbcMesAnterior() && (($arLicencia->getLicenciaTipoRel()->getRemunerada()))) {
                                                $arrIbc = $em->getRepository(RhuAporteDetalle::class)->ibcMesAnterior($arLicencia->getFechaDesde()->format('Y'), $arLicencia->getFechaDesde()->format('m'), $arEmpleado->getCodigoEmpleadoPk());
                                                $arLicencia->setVrIbcMesAnterior($arrIbc['ibc']);
                                                $arLicencia->setDiasIbcMesAnterior($arrIbc['dias']);
                                            }
                                        }
                                        if ($salarioEmpleado <= $arContrato->getVrSalario()) {
                                            $floVrLicencia = $intDiasCobro * $douVrDiaSalarioMinimo;
                                        } else {
                                            if ($arEmpleado->getVrSalario()) {
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
                                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_detalle', array("id"=>$arLicencia->getCodigoLicenciaPk())));
                                    }
                                } else {
                                    Mensaje::error("La fecha de inicio del contrato es mayor a la licencia");
                                }
                            } else {
                                Mensaje::error("Existe otra licencia en este rango de fechas");
                            }
                        } else {
                            Mensajes::error("Hay incapacidades que se cruzan con la fecha de la licencia");
                        }
                    } else {
                        Mensajes::error("La fecha desde debe ser inferior o igual a la fecha hasta");
                    }
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista', ['id' => $arLicencia->getCodigoLicenciaPk()]));
                } else {
                    Mensajes::error("El contrato no existe");
                }

            } else {
                Mensajes::error("El empleado no existe");
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

}