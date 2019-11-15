<?php


namespace App\Controller\Turno\Utilidad\Operacion\Analizar;


use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurProgramacionInconsistencia;
use App\Entity\Turno\TurTurno;
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

class InconsistenciasController extends AbstractController
{

    /**
     * @Route("turno/utilidad/importar/analizar/inconsistencias", name="turno_utilidad_operacion_analizar_inconsistencias")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $fechaActual = new \DateTime('now');
        $primerDiaDelMes = $fechaActual->format('Y/m/') . "01";
        $intUltimoDia  = date("d", (mktime(0, 0, 0, $fechaActual->format('m') + 1, 1, $fechaActual->format('Y')) - 1));
        $ultimoDiaDelMes = $fechaActual->format('Y/m/') . $intUltimoDia;
        $form = $this->createFormBuilder()
            ->add('txtEmpleado', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'data'=>date_create($primerDiaDelMes), 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'data'=>date_create($ultimoDiaDelMes),'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('grupo', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.codigoGrupoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('turnosDobles', CheckboxType::class, array('required'  => false))
            ->add('sinProgramacion', CheckboxType::class, array('required'  => false) )
            ->add('horariosCruzados', CheckboxType::class, array('required'  => false) )
            ->add('turnosRepetidos', CheckboxType::class, array('required'  => false))
            ->add('sinTurno', CheckboxType::class, array('required'  => false))
            ->add('novedad', CheckboxType::class, array('required'  => false))
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnGenerar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $sinProgramacion = $form->get('sinProgramacion')->getData();
                $turnosDobles = $form->get('turnosDobles')->getData();
                $sinTurno = $form->get('sinTurno')->getData();
                $novedad = $form->get('novedad')->getData();
                $horariosCruzados = $form->get('horariosCruzados')->getData();
                $turnosRepetidos = $form->get('turnosRepetidos')->getData();
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                $arGrupo = $form->get('grupo')->getData();
                $codigoGrupo = "";
                $codigoEmpleado = "";
                if ($arGrupo) {
                    $codigoGrupo = $arGrupo->getCodigoGrupoPk();
                }
                if ($form->get('txtEmpleado')->getData() != "" && is_numeric($form->get('txtEmpleado')->getData())) {
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($form->get('txtEmpleado')->getData());
                    if ($arEmpleado) {
                        $codigoEmpleado = $arEmpleado->getCodigoEmpleadoPk();
                    } else {
                        $codigoEmpleado = null;
                    }
                }
                $em->getRepository(TurProgramacionInconsistencia::class)->limpiarInconsistenciasUsuario($this->getUser()->getUserName());
                $strAnio = $dateFechaDesde->format('Y');
                $strMes = $dateFechaDesde->format('m');
                if ($sinProgramacion) {
                    $this->empleadosSinProgramacionesMes($dateFechaDesde, $dateFechaHasta, $strAnio, $strMes, $this->getUser()->getUsername(), $codigoGrupo, $codigoEmpleado);
                }
                if ($turnosDobles) {
                    $this->empleadosTurnoDoble($dateFechaDesde, $dateFechaHasta, $this->getUser()->getUsername(), $codigoGrupo, $codigoEmpleado);
                }
                if ($horariosCruzados) {
                    $this->horariosCruzados($dateFechaDesde, $dateFechaHasta, $this->getUser()->getUsername(), $codigoGrupo, $codigoEmpleado);
                }
                if ($sinTurno) {
                    $this->empleadosSinTurno($dateFechaDesde, $dateFechaHasta, $this->getUser()->getUsername(), $codigoGrupo, $codigoEmpleado);
                }
                if ($turnosRepetidos) {
                    $this->empleadosTurnoRepetido($dateFechaDesde, $dateFechaHasta, $this->getUser()->getUsername(),  $codigoGrupo, $codigoEmpleado);
                }
                if ($novedad) {
                    $this->inconsitenciasNovedadad($dateFechaDesde, $dateFechaHasta, $strAnio, $strMes, $this->getUser()->getUsername(), $codigoGrupo, $codigoEmpleado);
                }
                set_time_limit(60);
                return $this->redirect($this->generateUrl('turno_utilidad_operacion_analizar_inconsistencias'));
            }
            if ($form->get('btnExcel')->isClicked()) {
            }
            if ($form->get('btnEliminar')->isClicked()) {
            }


        }
        $arProgramacionInconsistencias = $paginator->paginate($em->getRepository(TurProgramacionInconsistencia::class)->lista($this->getUser()->getUsername()), $request->query->getInt('page', 1), 30);
        return $this->render('turno/utilidad/operacion/analizar/inconsistencias.html.twig', [
            'arProgramacionInconsistencias' => $arProgramacionInconsistencias,
            'form' => $form->createView()]);
    }

    /**
     * @param $dateFechaHasta \DateTime
     * @param $anio
     * @param $mes
     * @param $usuario
     * @param $codigoGrupo
     * @param $codigoRecurso
     */
    private function empleadosSinProgramacionesMes($dateFechaDesde, $dateFechaHasta, $anio, $mes, $usuario, $codigoGrupo, $codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleados = $em->getRepository(TurProgramacionInconsistencia::class)
            ->getQueryRecursosFechaIngreso($dateFechaDesde->format("Y-m-d"), $dateFechaHasta->format("Y-m-d"), $codigoEmpleado, $codigoGrupo);
        foreach ($arEmpleados as $arEmpleado) {
            $arProgramaciones = $em->getRepository(TurProgramacionInconsistencia::class)
                ->getProgramacionesRecurso($arEmpleado->getCodigoEmpleadoPk(), $anio, $mes);
            if (count($arProgramaciones) == 0) {
                $mensaje = "El emplado ({$arEmpleado->getCodigoEmpleadoPk()}) {$arEmpleado->getNombreCorto()} no registra programaciones para el mes";
                $inconsistencia = "Empleado sin programacion en el mes";
                $infoRecurso = [
                    'numeroIdentificacion' => $arEmpleado->getNumeroIdentificacion(),
                    'codigoEmpleadoPk' => $arEmpleado->getCodigoEmpleadoPk(),
                ];
                $this->reportarEmpleado(0, $mes, $anio, $inconsistencia, $mensaje, $infoRecurso, $usuario);
            }
        }
        return true;
    }

    /**
     * @param $arRecursos
     * @param $dateFechaDesde
     * @param $dateFechaHasta
     * @param $usuario
     * @param $codigoCentroCosto
     * @return bool
     */
    private function empleadosSinTurno($dateFechaDesde, $dateFechaHasta, $usuario, $codigoGrupo, $codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        $primerDia = $dateFechaDesde->format("j");
        $ultimoDia = $dateFechaHasta->format("j");
        $anio = $dateFechaDesde->format("Y");
        $mes = $dateFechaDesde->format("m");

        $programaciones = $em->getRepository(   TurProgramacionInconsistencia::class)->getTotalProgramacionesPorDia($anio, $mes, $primerDia, $ultimoDia, $codigoEmpleado, $codigoGrupo);
        foreach ($programaciones AS $programacion) {
            for ($i = $primerDia; $i <= $ultimoDia; $i++) {
                if ($programacion["dia{$i}"] == 0) {
                    $arProgramacionInconsistencia = new TurProgramacionInconsistencia();
                    $arProgramacionInconsistencia->setInconsistencia('Sin turno asignado');
                    $arProgramacionInconsistencia->setDetalle("El empleado con código: " . $programacion['codigoEmpleadoFk'].
                        ", número de identificación: " . $programacion['numeroIdentificacion'] . ", " .
                        $programacion['nombreEmpleado'] . " dia " . $i);
                    $arProgramacionInconsistencia->setNumeroIdentificacion($programacion['numeroIdentificacion']);
                    $arProgramacionInconsistencia->setDia($i);
                    $arProgramacionInconsistencia->setMes($mes);
                    $arProgramacionInconsistencia->setAnio($anio);
                    $arProgramacionInconsistencia->setCodigoEmpleadoFk($programacion['codigoEmpleadoFk']);
                    $arProgramacionInconsistencia->setCodigoRecursoGrupoFk(null);
                    $arProgramacionInconsistencia->setUsuario($usuario);
                    $em->persist($arProgramacionInconsistencia);
                }
            }
        }
        $em->flush();
        return TRUE;
    }

    private function horariosCruzados($desde, $hasta, $usuario, $codigoGrupo, $codigoEmpleado)
    {
        $anio = $desde->format("Y");
        $mes = $desde->format("m");
        set_time_limit(0);  # Esto puede tardar un buen tiempo, ya que por cada servicio se listan todos sus detalles.
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $empleados = $em->getRepository(TurProgramacionInconsistencia::class)->getRecursosProgramados($anio, $mes, $codigoGrupo, $codigoEmpleado);
        foreach ($empleados AS $empleado) {
            $programaciones = $em->getRepository(TurProgramacion::class)->programacionesEmpleadoFechaInconsistencia($empleado['codigoEmpleadoPk'], $anio, $mes);
            if (!$programaciones || count($programaciones) == 1) {
                continue;
            }
            for ($dia = 1; $dia <= 31; $dia++) {
                $this->validarCrucehorarios($dia, $mes, $anio, $empleado, $programaciones, $usuario);
            }
        }
        return true;
    }

    private function validarCrucehorarios($dia, $mes, $anio, $empleado, $arrProgramaciones, $usuario)
    {
        $hoy = date("Y-m-d");
        $horaEntrada = new \DateTime(date("Y-m-d 00:00:00", strtotime($hoy . " + 1 days")));
        $horaSalida = new \DateTime(date("Y-m-d 00:00:00", strtotime(" 1900-01-01 00:00:00 - 1 days")));
        $turnosAnteriores = [];
        $cruce = false;
        $primeraIteracion = true;

        foreach ($arrProgramaciones AS $programacion) {
            if (!$programacion["desdeDia{$dia}"] || !$programacion["hastaDia{$dia}"] || $programacion["turnoDiaDescanso{$dia}"]) {
                continue;
            }
            $horaInicial = $programacion["desdeDia{$dia}"];
            $horaFinal = $programacion["hastaDia{$dia}"];
            if ($horaInicial > $horaFinal) {
                $fechaTmp = $horaFinal->format("Y-m-d H:i:s");
                $horaFinal = new \DateTime(date("Y-m-d H:i:s", strtotime($fechaTmp . " +1 day")));
            }
            # Aqui evaluamos el cruce de horarios.
            if (!$primeraIteracion) {
            # si la hora de inicio esta entre la hora inicial y final del turno anterior, entonces hay cruce.
//            if(($horaInicial > $horaEntrada && $horaInicial < $horaSalida) || ($horaInicial == $horaEntrada && $horaFinal == $horaSalida)) {
                $cruce = $this->validarTurnosDia([
                    'horaInicial' => $horaInicial,
                    'horaFinal' => $horaFinal,
                ], $turnosAnteriores);
                if ($cruce) {
                    $identificacion = $empleado['numeroIdentificacion'];
                    $nombre = $empleado['nombreCorto'];
                    $mensaje = "El empleado con código: {$empleado['codigoRecursoPk']}, Identificacion: {$identificacion}, {$nombre} dia {$dia}";
                    $this->reportarEmpleado($dia, $mes, $anio, "Horario cruzado", $mensaje, $empleado, $usuario);
                    break; # Se hay un solo cruce de horario para el día no continuamos validando.
                }
            } else {
                $primeraIteracion = false;
                $turnosAnteriores[] = [
                    'horaInicial' => $horaInicial,
                    'horaFinal' => $horaFinal,
                ];
            }

        }
    }

    private function validarTurnosDia($turno, &$turnosAnteriores)
    {
        $cruce = false;
        foreach ($turnosAnteriores AS $turnoAnterior) {
            $cruce = ($turno['horaInicial'] > $turnoAnterior['horaInicial'] && $turno['horaInicial'] < $turnoAnterior['horaFinal']) ||
                ($turno['horaFinal'] > $turnoAnterior['horaInicial'] && $turno['horaFinal'] < $turnoAnterior['horaFinal']) ||
                ($turno['horaInicial'] == $turnoAnterior['horaInicial'] && $turno['horaFinal'] == $turnoAnterior['horaFinal']);
            if ($cruce) {
                break;
            } else {
                $turnosAnteriores[] = $turno;
            }
        }
        return $cruce;
    }

    /**
     * Esta función permite validar los turnos dobles todos los recursos.
     * @param $dateFechaDesde
     * @param $dateFechaHasta
     * @param $usuario
     * @param $codigoCentroCosto
     * @return bool
     */
    private function empleadosTurnoDoble($dateFechaDesde, $dateFechaHasta, $usuario, $codigoGrupo, $codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        $primerDia = $dateFechaDesde->format("j");
        $ultimoDia = $dateFechaHasta->format("j");
        $anio = $dateFechaDesde->format("Y");
        $mes = $dateFechaDesde->format("m");

        $arProgramaciones = $em->getRepository(TurProgramacion::class)->EmpleadosTurnosDobles($anio, $mes, $primerDia, $ultimoDia,  $codigoGrupo, "",$codigoEmpleado);
        $diasRecursos = []; # Se guardan todos los días y turnos que tiene el recurso para ese día.
        $recursosAReportar = []; # se guardan las inconsistencias de los recursos por día.

        foreach ($arProgramaciones AS $programacion) {
            $codRecurso = $programacion["codigoEmpleadoFk"];
            if ($codRecurso == "") {
                continue;
            }
            for ($i = $primerDia; $i <= $ultimoDia; $i++) {
                $turnoActual = preg_replace("/(\n|\t|\s)/", "", $programacion["turnoDia{$i}"]); # Realizamos el preg replace para eliminar cualquier caracter especial
                # que pueda entorpecer el proceso.
                $esComplementario = $programacion["dia{$i}EsComplementario"];
                $ocupado = $turnoActual != "" && isset($diasRecursos[$codRecurso]["dia{$i}"]);

                if (!empty($turnoActual) && $ocupado && !$esComplementario) {
                    $recursosAReportar[$codRecurso][] = $i;
                } else if ($turnoActual != "" && $esComplementario == 0) {
                    $diasRecursos[$codRecurso]["dia{$i}"][] = $turnoActual;
                }
            }
        }

        foreach ($recursosAReportar AS $codigoEmpleado => $dias) {
            if ($codigoEmpleado == "") {
                continue;
            }
            foreach ($dias AS $dia) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado);
                if (!$arEmpleado) {
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado);
                }
                $arProgramacionInconsistencia = new TurProgramacionInconsistencia();
                $arProgramacionInconsistencia->setInconsistencia('Asignacion doble de turno');
                $arProgramacionInconsistencia->setDetalle("El empleado con código: " . $codigoEmpleado . " " .
                    $arEmpleado->getNombreCorto() . " dia " . $dia);
                $arProgramacionInconsistencia->setDia($dia);
                $arProgramacionInconsistencia->setMes($mes);
                $arProgramacionInconsistencia->setAnio($anio);
                $arProgramacionInconsistencia->setCodigoEmpleadoFk($codigoEmpleado);
                $arProgramacionInconsistencia->setCodigoRecursoGrupoFk(null);
                $arProgramacionInconsistencia->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                $arProgramacionInconsistencia->setUsuario($usuario);
                $em->persist($arProgramacionInconsistencia);
            }
        }
        $em->flush();
        return true;
    }

    /**
     * Esta función permite validar los turnos repetidos de un recurso.
     * @param string $dateFechaDesde
     * @param string $dateFechaHasta
     * @param string $usuario
     * @param string $codigoCentroCosto
     * @return bool
     */
    private function empleadosTurnoRepetido($dateFechaDesde, $dateFechaHasta, $usuario, $codigoGrupo, $codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        $primerDia = $dateFechaDesde->format("j");
        $ultimoDia = $dateFechaHasta->format("j");
        $anio = $dateFechaDesde->format("Y");
        $mes = $dateFechaDesde->format("m");

        $programaciones = $em->getRepository(TurProgramacionInconsistencia::class)->getRecursosTurnosRepetidos($anio, $mes, $codigoEmpleado, $codigoGrupo);
        $diasRcursos = [];
        $recursosAReportar = [];
        foreach ($programaciones AS $programacion) {
            for ($i = $primerDia; $i <= $ultimoDia; $i++) {
                $turnoActual = call_user_func_array([$programacion, "getDia{$i}"], []);
                if ($turnoActual != "" && isset($diasRcursos[$programacion->getCodigoEmpleadoFk()]["dia{$i}"]) && in_array($turnoActual, $diasRcursos[$programacion->getCodigoEmpleadoFk()]["dia{$i}"])) {
                    $recursosAReportar[$programacion->getCodigoEmpleadoFk()][] = $i;
                } else {
                    $diasRcursos[$programacion->getCodigoEmpleadoFk()]["dia{$i}"][] = $turnoActual;
                }
            }
        }
        # La razón por la cual se recorren de nuevo los días a reportar, es para que salga el reporte ordenado
        # por recurso
        foreach ($recursosAReportar AS $codigoEmpleado => $dias) {
            if ($codigoEmpleado == "") {
                continue;
            }
            foreach ($dias AS $dia) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado);
                $arProgramacionInconsistencia =new TurProgramacionInconsistencia;
                $arProgramacionInconsistencia->setInconsistencia('Asignacion doble de turno');
                $arProgramacionInconsistencia->setDetalle("El empleado con código: " . $codigoEmpleado . " " .
                    $arEmpleado->getNombreCorto() . " dia " . $dia);
                $arProgramacionInconsistencia->setDia($dia);
                $arProgramacionInconsistencia->setMes($mes);
                $arProgramacionInconsistencia->setAnio($anio);
                $arProgramacionInconsistencia->setCodigoEmpleadoFk($codigoEmpleado);
                $arProgramacionInconsistencia->setCodigoRecursoGrupoFk(null);
                $arProgramacionInconsistencia->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                $arProgramacionInconsistencia->setUsuario($usuario);
                $em->persist($arProgramacionInconsistencia);
            }
        }
        $em->flush();
        return true;
    }

    private function inconsitenciasNovedadad($dateFechaDesde, $dateFechaHasta, $anio, $mes, $usuario, $codigoGrupo, $codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        $primerDia = (int)$dateFechaDesde->format("j");
        $ultimoDia = (int)$dateFechaHasta->format("j");
        $arrNovedadDias = [];
        $arProgramaciones = $em->getRepository(TurProgramacionInconsistencia::class)->getProgramacionNovedades($anio, $mes, $codigoEmpleado, $codigoGrupo);
        // aqui se recorre toda la programacion
        foreach ($arProgramaciones as $arProgramacion) {
            for ($i = $primerDia; $i <= $ultimoDia; $i++) {
                $codigoTurno = call_user_func_array([$arProgramacion, "getDia" . $i], []);
                // valida que si exista el turno
                if ($codigoTurno && $arProgramacion->getCodigoEmpleadoFk()) {
                    $arTurno = $em->getRepository(TurTurno::class)->find($codigoTurno);
                    // aqui se valida si el turno es incapacidad, licencia o licencia no remunerada
                    if ($arTurno->getNovedad() && ($arTurno->getIncapacidad() || $arTurno->getLicencia() || $arTurno->getLicenciaNoRemunerada())) {
                        $tipo = $arTurno->getIncapacidad() ? "incapacidad" : ($arTurno->getLicencia() ? "licencia" : ($arTurno->getLicenciaNoRemunerada() ? "licenciaNoRemunerada" : null));
                        $arrNovedadDias[$arProgramacion->getCodigoEmpleadoFk()][$i] = ["codigoTurno" => $arTurno->getCodigoTurnoPk(), "tipo" => $tipo, "fecha" => $anio . "/" . $mes . "/" . $i];
                    }
                }
            }
        }
        // aqui se ordena los dias de las novedades de menor a mayor
        $arrAux = [];
        foreach ($arrNovedadDias as $codigo => $arr) {
            ksort($arr);
            $arrAux[$codigo] = $arr;
        }
        // fin orden
        $arrNovedad = [];
        $contador = 0;
        $turnoAnt = null;
        $diaAct = 0;
        $diaAnt = 0;
        foreach ($arrAux as $codEmpleado => $arr) {
            if ($codEmpleado == "") {
                exit();
            }
            foreach ($arr as $dia => $novedad) {
                $diaAct = $dia;
                if (($turnoAnt == $novedad["codigoTurno"] && ($diaAnt != ($diaAct - 1)))) {
                    $contador++;
                    $turnoAnt = null;
                }
                if ($turnoAnt == null) {
                    if (!isset($arrNovedad[$contador]["fechaDesde"])) {
                        $arrNovedad[$contador] = ["codigoEmpleado" => $codEmpleado, "fechaDesde" => $novedad["fecha"], "fechaHasta" => $novedad["fecha"], "tipo" => $novedad["tipo"]];
                        $turnoAnt = $novedad["codigoTurno"];
                    }
                } elseif ($turnoAnt == $novedad["codigoTurno"] && ($turnoAnt == $novedad["codigoTurno"] && ($diaAnt == ($diaAct - 1)))) {
                    $arrNovedad[$contador]["fechaHasta"] = $novedad["fecha"];
                    $turnoAnt = $novedad["codigoTurno"];
                } elseif ($turnoAnt != $novedad["codigoTurno"]) {
                    $contador++;
                    //$turnoAnt = null;
                    if (!isset($arrNovedad[$contador]["fechaDesde"])) {
                        $arrNovedad[$contador] = ["codigoEmpleado" => $codEmpleado, "fechaDesde" => $novedad["fecha"], "fechaHasta" => $novedad["fecha"], "tipo" => $novedad["tipo"]];
                        $turnoAnt = $novedad["codigoTurno"];
                    }
                }
                $diaAnt = $diaAct;
            }
        }
        if ($arrNovedad) {
            foreach ($arrNovedad as $novedadRecurso) {
                $arrError = [];
                $fechaDesde = date_create($novedadRecurso['fechaDesde']);
                $fechaHasta = date_create($novedadRecurso['fechaHasta']);
                $codigoEmpleado = $novedadRecurso['codigoEmpleado'];

                $arNovedad = [];
                if ($novedadRecurso['tipo'] == "incapacidad") {
                    $arNovedad = $em->getRepository(RhuIncapacidad::class)->periodo($fechaDesde, $fechaHasta, $codigoEmpleado);
                } elseif ($novedadRecurso['tipo'] == "licencia") {
                    $arNovedad = $em->getRepository(RhuLicencia::class)->periodo($fechaDesde, $fechaHasta, $codigoEmpleado);
                } elseif ($novedadRecurso['tipo'] == "licenciaNoRemunerada") {
                    $arNovedad = $em->getRepository(RhuLicencia::class)->periodo($fechaDesde, $fechaHasta, $codigoEmpleado);
                }

                /** @var $arIncapacidad RhuIncapacidad */
                foreach ($arNovedad AS $arNovedad) {
                    $respuesta = false;
                    if ($novedadRecurso['tipo'] == "incapacidad" || $novedadRecurso['tipo'] == "licencia") {
                        if ($arNovedad->getFechaDesde() == $fechaDesde && $arNovedad->getFechaHasta() == $fechaHasta) {
                            $respuesta = true;
                        } elseif ($arNovedad->getFechaDesde() == $fechaDesde && ($arNovedad->getFechaHasta()->format('n') > $fechaHasta->format('n'))) {
                            $respuesta = true;
                        } elseif ($arNovedad->getFechaDesde()->format('n') < $fechaDesde->format('n') && $arNovedad->getFechaHasta()->format('n') > $fechaHasta->format('n')) {
                            $respuesta = true;
                        } elseif ($arNovedad->getFechaDesde()->format('n') < $fechaDesde->format('n') && $arNovedad->getFechaHasta() <= $fechaHasta && $arNovedad->getEstadoProrroga()) {
                            $respuesta = true;
                        } elseif ($arNovedad->getFechaDesde()->format('n') < $fechaDesde->format('n') && $arNovedad->getFechaHasta() <= $fechaHasta) {
                            $respuesta = true;
                        } elseif ($arNovedad->getFechaDesde() >= $fechaDesde && ($arNovedad->getFechaHasta() >= $fechaHasta || $arNovedad->getFechaHasta() <= $fechaHasta) && $arNovedad->getEstadoProrroga()) {
                            $respuesta = true;
                        } elseif ($arNovedad->getFechaDesde() >= $fechaDesde && ($arNovedad->getFechaHasta() >= $fechaHasta || $arNovedad->getFechaHasta() <= $fechaHasta)) {
                            $respuesta = true;
                        } elseif ($arNovedad->getFechaDesde() == $fechaDesde && $fechaHasta >= $arNovedad->getFechaHasta()) {
                            $respuesta = true;
                        }
                    } else {
                        if ($arNovedad->getLicenciaTipoRel()->getSuspensionContratoTrabajo()) {
                            if ($arNovedad->getFechaDesde() == $fechaDesde && $arNovedad->getFechaHasta() == $fechaHasta) {
                                $respuesta = true;
                            } elseif ($arNovedad->getFechaDesde() == $fechaDesde && ($arNovedad->getFechaHasta()->format('n') > $fechaHasta->format('n'))) {
                                $respuesta = true;
                            } elseif ($arNovedad->getFechaDesde()->format('n') < $fechaDesde->format('n') && $arNovedad->getFechaHasta()->format('n') > $fechaHasta->format('n')) {
                                $respuesta = true;
                            } elseif ($arNovedad->getFechaDesde()->format('n') < $fechaDesde->format('n') && $arNovedad->getFechaHasta() <= $fechaHasta && $arNovedad->getEstadoProrroga()) {
                                $respuesta = true;
                            } elseif ($arNovedad->getFechaDesde()->format('n') < $fechaDesde->format('n') && $arNovedad->getFechaHasta() <= $fechaHasta) {
                                $respuesta = true;
                            } elseif ($arNovedad->getFechaDesde() >= $fechaDesde && ($arNovedad->getFechaHasta() >= $fechaHasta || $arNovedad->getFechaHasta() <= $fechaHasta) && $arNovedad->getEstadoProrroga()) {
                                $respuesta = true;
                            } elseif ($arNovedad->getFechaDesde() >= $fechaDesde && ($arNovedad->getFechaHasta() >= $fechaHasta || $arNovedad->getFechaHasta() <= $fechaHasta)) {
                                $respuesta = true;
                            } elseif ($arNovedad->getFechaDesde() == $fechaDesde && $fechaHasta >= $arNovedad->getFechaHasta()) {
                                $respuesta = true;
                            }
                        }
                    }
                    if (!$respuesta) {
                        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado);
                        $arProgramacionInconsistencia = new TurProgramacionInconsistencia();
                        $arProgramacionInconsistencia->setInconsistencia('Error en novedades');
                        $arProgramacionInconsistencia->setDetalle("No coincide la fecha de " . $novedadRecurso['tipo'] . " de RRHH con TURNOS del Recurso " . $codigoEmpleado . " " .
                            $arEmpleado->getNombreCorto() . " fecha novedad en turno desde " . $novedadRecurso['fechaDesde'] . " hasta " . $novedadRecurso['fechaHasta']);
                        $arProgramacionInconsistencia->setDia(0);
                        $arProgramacionInconsistencia->setMes($mes);
                        $arProgramacionInconsistencia->setAnio($anio);
                        $arProgramacionInconsistencia->setCodigoEmpleadoFk($codigoEmpleado);
                        $arProgramacionInconsistencia->setCodigoRecursoGrupoFk(null);
                        $arProgramacionInconsistencia->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arProgramacionInconsistencia->setUsuario($usuario);
                        $em->persist($arProgramacionInconsistencia);
                    }
                }
            }
            $em->flush();
        }
    }

    private function reportarEmpleado($dia, $mes, $anio, $inconsistencia, $mensaje, $infoRecurso, $usuario)
    {
        $em = $this->getDoctrine()->getManager();
        $arProgramacionInconsistencia = new TurProgramacionInconsistencia();
        $arProgramacionInconsistencia->setInconsistencia($inconsistencia);
        $arProgramacionInconsistencia->setDetalle($mensaje);
        $arProgramacionInconsistencia->setNumeroIdentificacion($infoRecurso["numeroIdentificacion"]);
        $arProgramacionInconsistencia->setDia($dia);
        $arProgramacionInconsistencia->setMes($mes);
        $arProgramacionInconsistencia->setAnio($anio);
        $arProgramacionInconsistencia->setCodigoEmpleadoFk($infoRecurso['codigoEmpleadoPk']);
        $arProgramacionInconsistencia->setCodigoRecursoGrupoFk(null);
        $arProgramacionInconsistencia->setUsuario($usuario);
        $em->persist($arProgramacionInconsistencia);
        $em->flush();
    }
}