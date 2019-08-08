<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Vacaciones;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuNovedad;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Form\Type\RecursoHumano\VacacionType;
use App\Formato\RecursoHumano\Vacaciones;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VacacionesController extends ControllerListenerGeneral
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Vacaciones");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuVacacion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_vacacion_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/vacacion/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
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
                                            $arVacacionAdicional->setPagoConceptoRel($arAdicional->getPagoConceptoRel());
                                            $arVacacionAdicional->setVrDeduccion($arAdicional->getValor());
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
     * @Route("recursohumano/movimiento/nomina/vacacion/detalle/{id}", name="recursohumano_movimiento_nomina_vacacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVacacion = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arVacacion->getEstadoAutorizado(), $arVacacion->getEstadoAprobado(), $arVacacion->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formatoVacaciones = new Vacaciones();
                $formatoVacaciones->Generar($em, $id);
            }

        }
        return $this->render('recursohumano/movimiento/nomina/vacacion/detalle.html.twig', [
            'arVacacion' => $arVacacion,
            'form' => $form->createView()
        ]);
    }
}

