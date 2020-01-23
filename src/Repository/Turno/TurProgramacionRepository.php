<?php

namespace App\Repository\Turno;


use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmVisita;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurNovedadInconsistencia;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurProgramacionRepository extends ServiceEntityRepository
{

    private $horasDiurnasD = 0;
    private $horasNocturnasD = 0;
    private $horasDiurnasP = 0;
    private $horasNocturnasP = 0;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurProgramacion::class);
    }

    public function detalleProgramacion($codigoPedido)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidad')
            ->addSelect('pu.codigoPuestoPk as puestoCodigo')
            ->addSelect('pu.nombre as puestoNombre')
            ->addSelect('c.nombre as concepto')
            ->addSelect('pet.nombre as pedidoTipo')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->leftJoin('pd.modalidadRel', 'm')
            ->leftJoin('pd.puestoRel', 'pu')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pedidoRel', 'p')
            ->leftJoin('p.clienteRel', 'cl')
            ->leftJoin('p.pedidoTipoRel', 'pet')
            ->where('pd.codigoPedidoFk=' . $codigoPedido);
        $arrPedidoDetalles = $queryBuilder->getQuery()->getResult();
        $c = 0;
        foreach ($arrPedidoDetalles as $arrPedidoDetalle) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, 'p')
                ->select('p.codigoProgramacionPk')
                ->addSelect('p.horasDiurnas')
                ->addSelect('p.horasNocturnas')
                ->addSelect('p.anio')
                ->addSelect('p.mes')
                ->addSelect('p.codigoEmpleadoFk')
                ->addSelect('e.nombreCorto as empleadoNombreCorto')
                ->leftJoin('p.empleadoRel', 'e')
                ->leftJoin('p.pedidoDetalleRel', 'pd')
                ->where('p.codigoPedidoDetalleFk = ' . $arrPedidoDetalle['codigoPedidoDetallePk']);
            for ($i = 1; $i <= 31; $i++) {
                $queryBuilder->addSelect("p.dia{$i}");
            }
            $arProgramaciones = $queryBuilder->getQuery()->getResult();
            $arrPedidoDetalles[$c]['arProgramaciones'] = $arProgramaciones;
            $c++;
        }

        return $arrPedidoDetalles;
    }

    public function generar($arPedidoDetalle)
    {
        $em = $this->getEntityManager();
        $fechaProgramacion = new \DateTime('now');
        $arUsuario = null;
        //$arConfiguracion = $em->getRepository("BrasaTurnoBundle:TurConfiguracion")->find(1);
        $validarHorasProgramadas = true;
        //$arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);

        $horasDiurnas = $arPedidoDetalle->getHorasDiurnasProgramadas();
        $horasNocturnas = $arPedidoDetalle->getHorasNocturnasProgramadas();
        $horasDiurnasContratadas = $arPedidoDetalle->getHorasDiurnas();
        $horasNocturnasContratadas = $arPedidoDetalle->getHorasNocturnas();
        $intDiaInicial = $arPedidoDetalle->getDiaDesde();
        $intDiaFinal = $arPedidoDetalle->getDiaHasta();
        //$arFestivos =  $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacion->getFecha()->format('Y-m-') . $intDiaInicial, $arProgramacion->getFecha()->format('Y-m-') . $intDiaFinal);
        $arrFestivos = $em->getRepository(TurFestivo::class)->fechaArray($fechaProgramacion->format('Y-m-') . $intDiaInicial, $fechaProgramacion->format('Y-m-') . $intDiaFinal);
        $strMesAnio = $fechaProgramacion->format('Y/m');


        $arPrototipos = $em->getRepository(TurPrototipo::class)->findBy(['codigoContratoDetalleFk' => $arPedidoDetalle->getCodigoContratoDetalleFk()]);

        $intMes = $arPedidoDetalle->getMes();
        $intAnio = $arPedidoDetalle->getAnio();
        $intDiaInicial = $arPedidoDetalle->getDiaDesde();
        $intDiaFinal = $arPedidoDetalle->getDiaHasta();
        $intAnio = $arPedidoDetalle->getAnio();
        $fechaProgramacion = new \DateTime("{$intAnio}-{$intMes}-{$intDiaInicial}");    # Capturamos la fecha de la programacion basados en la fecha inicial del pedido.
        $this->horasDiurnasD = $horasDiurnas = $arPedidoDetalle->getHorasDiurnas();     # Capturamos diurnas y nocturnas para ir descontando cada que se programe exitosamente un turno.
        $this->horasNocturnasD = $horasNocturnas = $arPedidoDetalle->getHorasNocturnas();
        $totalHorasNocturnasP = 0;
        $totalHorasDiurnasP = 0;
        $arrError = [];
        foreach ($arPrototipos AS $arPrototipo) {     # Por cada uno de los recursos asignados al detalle del pedido es que realizamos una programacion.
            $this->horasDiurnasP = 0;   # Reiniciamos los acumuladores de horas programadas para el siguiente registro.
            $this->horasNocturnasP = 0;
            $arSecuencia = $arPrototipo->getSecuenciaRel();
            if ($arSecuencia) {
                $arrSecuencias = FuncionesController::turnosSecuencia($arSecuencia);
                $arProgramacion = new TurProgramacion();
                $arProgramacion->setPedidoDetalleRel($arPedidoDetalle);
                $arProgramacion->setPedidoRel($arPedidoDetalle->getPedidoRel());
                $arProgramacion->setPuestoRel($arPedidoDetalle->getPuestoRel());
                $arProgramacion->setAnio($arPedidoDetalle->getAnio());
                $arProgramacion->setMes($arPedidoDetalle->getMes());
                $arProgramacion->setContratoRel($arPrototipo->getEmpleadoRel()->getContratoRel());
                $arProgramacion->setEmpleadoRel($arPrototipo->getEmpleadoRel());
                //$arProgramacion->setSecuenciaRel($arPrototipo->getSecuenciaRel());
                $fechaInicial = $arPrototipo->getFechaInicioSecuencia()->format('Y-m-d'); # Por cada recurso capturamos cual es la fecha en que inicia su programacion
                $intDiaInicialRecurso = intval($arPrototipo->getFechaInicioSecuencia()->format('d'));
                $intMesInicialRecurso = intval($arPrototipo->getFechaInicioSecuencia()->format('m'));
                $intervalo = $arSecuencia->getDias(); # Cada cuanto se repetira la secuencia.
                # Este metodo es el que se encarga de predecir en que posciion iniciara la secuencia segun el mes.
                $posicion = FuncionesController::devuelvePosicionInicialSecuencia($arPrototipo->getInicioSecuencia(), $intervalo, $fechaInicial, $fechaProgramacion->format('Y-m-d'));

                # Nos preparamos para ver si recien ingresa el empleado.
                $arEmpleado = $arPrototipo->getEmpleadoRel();
                $llenarIngresosHasta = 0;

                # Si hay empleado consultamos el contrato
                if ($arEmpleado) {
                    /*$codigoContrato = $arEmpleado->getCodigoContratoActivoFk() ? $arEmpleado->getCodigoContratoActivoFk() : $arEmpleado->getCodigoContratoUltimoFk();
                    if ($codigoContrato != null && $arContrato = $em->getRepository("BrasaRecursoHumanoBundle:RhuContrato")->find($codigoContrato)) {
                        $llenarIngresosHasta = $this->registrarDiasIngreso($arProgramacionDetalle, $arContrato);
                    }*/


                    for ($i = $intDiaInicial; $i <= $intDiaFinal; $i++) { # Recorremos los dias del mes del recurso actual.
                        # Para no llenar dias anteriores a la fecha de inicio de la secuencia del recurso.
                        if ($i < $intDiaInicialRecurso && intval($intAnio . $intMes) == intval($arPrototipo->getFechaInicioSecuencia()->format('Ym'))) {
                            continue;
                        }
                        $fecha = date_create(date("{$intAnio}-{$intMes}-{$i}"));
                        $turno = isset($arrSecuencias[$posicion]) ? $arrSecuencias[$posicion] : null;
                        # Validamos si el turno es un día de la semana.
                        if ($turnoDiaSemana = FuncionesController::validacionDiaSemama($fecha->format('N'), $arSecuencia)) {
                            $turno = $turnoDiaSemana;
                        }
                        # Validamos si el turno es festivo.
                        if ($turnoFestivo = FuncionesController::validacionFestivo($fecha->format('Y-m-d'), $arrFestivos, $arSecuencia)) {
                            $turno = $turnoFestivo;
                        }

                        # Validamos si el día es domingo y si el siguiente día es festivo.
                        if (intval($fecha->format('N')) == 7) {
                            if ($domingoFestivo = FuncionesController::validacionDomingoFestivo($fecha, $arrFestivos, $arSecuencia)) {
                                $turno = $domingoFestivo;
                            }
                        }

                        # Si hay que homologar el turno nos llegara A, B, C, D, etc. Por ende obtenemos el que le
                        # corresponde directamente del detalle del recurso.
                        if ($turno != null && $arSecuencia->getHomologar()) {
                            $nombreMetodo = "getTurno{$turno}";
                            if (method_exists($arPrototipo, $nombreMetodo)) {
                                # La funcion call user function nos permite llamar un metodo de un objeto
                                # utilizando un string ( que puede ser compuesto ).
                                # Mas informacion: http://php.net/manual/es/function.call-user-func-array.php
                                $turno = call_user_func_array([$arPrototipo, $nombreMetodo], []);
                            }
                        }

                        $posicion += 1;
                        if ($posicion > $intervalo) {
                            $posicion = 1;
                        }
                        # Validamos si se puede programar el turno.
                        /*if (!$this->sePuedeProgramarTurno($turno, $validarHorasProgramadas, $i, $llenarIngresosHasta)) {
                            continue;
                        }*/
                        # Aqui se setea cada uno de los dias de la clase programacionDetalle.
                        # Se setean segun el valor de $i (setDia1, setDia2 ...)
                        if (method_exists($arProgramacion, "setDia{$i}")) {
                            call_user_func_array([$arProgramacion, "setDia{$i}"], [$turno]);
                        }

                    }


                    /*$tieneIngresos = $arEmpleado
                        ? $this->getTieneIngresos($arProgramacionDetalle->getAnio(), $arProgramacionDetalle->getMes(), $llenarIngresosHasta, $arEmpleado->getCodigoEmpleadoPk())
                        : false;
                    if ($llenarIngresosHasta > 0) {
                        $arConfiguracion = $em->getRepository("BrasaTurnoBundle:TurConfiguracion")->find(1);
                        $turnoIngreso = $arConfiguracion->getTurnoIngreso();
                        for ($i = 1; $i < $llenarIngresosHasta; $i++) {
                            $turnoActual = call_user_func_array([$arProgramacionDetalle, "getDia{$i}"], []);
                            if ($turnoActual != "") {
                                call_user_func_array([$arProgramacionDetalle, "setDiaN{$i}"], [$turnoActual]);
                            }
                            if ($tieneIngresos) {
                                call_user_func_array([$arProgramacionDetalle, "setDia{$i}"], [null]);
                            } else {
                                call_user_func_array([$arProgramacionDetalle, "setDia{$i}"], [$turnoIngreso]);
                            }
                        }
                    }*/
                    $arProgramacion->setHorasDiurnas($this->horasDiurnasP);
                    $arProgramacion->setHorasNocturnas($this->horasNocturnasP);
                    $arProgramacion->setHoras($this->horasDiurnasP + $this->horasNocturnasP);
                    $em->persist($arProgramacion);
                    $totalHorasDiurnasP += $this->horasDiurnasP;
                    $totalHorasNocturnasP += $this->horasNocturnasP;
                }

            } else {
                $arrError[] = ["error" => "El recurso {$arPrototipo->getEmpleadoRel()->getNumeroIdentificacion()} no tiene asignada una secuencia del cliente {$arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto()} en el puesto {$arPedidoDetalle->getPuestoRel()->getNombre()}"];
            }
        }
        if ($arrError == null) {
            $arPedidoDetalle->setHorasProgramadas($totalHorasDiurnasP + $totalHorasNocturnasP);
            $arPedidoDetalle->setHorasDiurnasProgramadas($totalHorasDiurnasP);
            $arPedidoDetalle->setHorasNocturnasProgramadas($totalHorasNocturnasP);
            $arPedidoDetalle->setEstadoProgramado(1);
            $em->persist($arPedidoDetalle);
            $em->flush();
        }
        return $arrError;

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(TurProgramacion::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }

    public function listaEmpleadoSoporte($anio, $mes)
    {
        $em = $this->getEntityManager();
        $queryBuider = $em->createQueryBuilder()->from(TurProgramacion::class, "p")
            ->select('p.codigoEmpleadoFk')
            ->where("p.anio = '{$anio}'")
            ->andWhere("p.mes = '{$mes}'")
            ->groupBy("p.codigoEmpleadoFk");
        $arProgramaciones = $queryBuider->getQuery()->getResult();
        return $arProgramaciones;
    }

    public function programaciones()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, 'p')
            ->select('p.codigoProgramacionPk')
            ->addSelect('p.codigoPedidoDetalleFk')
            ->addSelect('p.horasDiurnas')
            ->addSelect('p.horasNocturnas')
            ->addSelect('p.anio')
            ->addSelect('p.mes')
            ->addSelect('p.codigoEmpleadoFk')
            ->addSelect('p.codigoPuestoFk')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('pe.codigoClienteFk')
            ->addSelect('pu.nombre as puestoNombre')
            ->addSelect('pu.direccion as puestoDireccion')
            ->addSelect('pu.codigoZonaFk as codigoZona')
            ->addSelect('p.complementario')
            ->addSelect('z.codigoZonaPk')
            ->addSelect('z.nombre as zonaNombre')
            ->addSelect('it.nombre as itemNombre')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.pedidoRel', 'pe')
            ->leftJoin('p.pedidoDetalleRel', 'ped')
            ->leftJoin('ped.itemRel', 'it')
            ->leftJoin('pe.clienteRel', 'cl')
            ->leftJoin('p.puestoRel', 'pu')
            ->leftJoin('pu.zonaRel', 'z')
            ->groupBy('pe.codigoClienteFk')
            ->groupBy('p.codigoPuestoFk')
            ->groupBy('p.codigoProgramacionPk');
        for ($i = 1; $i <= 31; $i++) {
            $queryBuilder->addSelect("p.dia{$i}");
        }
        if ($session->get('filtroTurProgramacionAnio') != null) {
            $queryBuilder->andWhere("p.anio = '{$session->get('filtroTurProgramacionAnio')}'");
        }
        if ($session->get('filtroTurProgramacionMes') != null) {
            $queryBuilder->andWhere("p.mes = '{$session->get('filtroTurProgramacionMes')}'");
        }
        if ($session->get('filtroRhuEmpleadoCodigoEmpleado') != null) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = '{$session->get('filtroRhuEmpleadoCodigoEmpleado')}'");
        }
        if ($session->get('filtroTurCodigoCliente') != null) {
            $queryBuilder->andWhere("cl.codigoClientePk = '{$session->get('filtroTurCodigoCliente')}'");
        }
        if ($session->get('filtroTurProgramacionCodigoPuesto') !=null){
            $queryBuilder->andWhere("p.codigoPuestoFk = '{$session->get('filtroTurProgramacionCodigoPuesto')}'");
        }
        if ($session->get('filtroTurProgramacionNuemeroPedido') !=null){
            $queryBuilder->andWhere("p.codigoPedidoFk = '{$session->get('filtroTurProgramacionNuemeroPedido')}'");
        }

        $queryBuilder->setMaxResults(5000);
        return $queryBuilder->getQuery()->getResult();
    }

    public function programacionEmpleado($codigoEmpleado, $anio, $mes)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, 'p')
            ->select('p.codigoProgramacionPk')
            ->addSelect('p.codigoPedidoDetalleFk')
            ->addSelect('p.horasDiurnas')
            ->addSelect('p.horasNocturnas')
            ->addSelect('p.anio')
            ->addSelect('p.mes')
            ->addSelect('p.codigoEmpleadoFk')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('ped.codigoClienteFk')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.pedidoRel', 'ped')
            ->leftJoin('ped.clienteRel', 'cl');
        for ($i = 1; $i <= 31; $i++) {
            $queryBuilder->addSelect("p.dia{$i}");
        }
        $queryBuilder->where('p.codigoEmpleadoFk=' . $codigoEmpleado)
            ->andWhere('p.anio = ' . $anio)
            ->andWhere('p.mes = ' . $mes);
        $arProgramaciones = $queryBuilder->getQuery()->getResult();
        return $arProgramaciones;
    }

    public function periodoDias($anio, $mes, $codigoEmpleado = "")
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurProgramacion::class, 'p')
            ->select('p.codigoProgramacionPk')
            ->addSelect('p.complementario')
            ->addSelect('p.adicional')
            ->addSelect('p.codigoPuestoFk')
            ->where("p.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("p.anio={$anio}")
            ->andWhere("p.mes={$mes}");
        for ($i = 1; $i <= 31; $i++) {
            $queryBuilder->addSelect('p.dia' . $i);
        }

        $arProgramaciones = $queryBuilder->getQuery()->getResult();
        return $arProgramaciones;

        /*$sql = "SELECT dia_1 , dia_2
FROM tur_programacion
LEFT JOIN tur_turno as tdia1
ON tur_programacion.dia_1 =tdia1.codigo_turno_pk
LEFT JOIN tur_turno as tdia2
ON tur_programacion.dia_2 =tdia2.codigo_turno_pk";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();*/

    }

    public function programacionPorMes($anio, $mes, $codigoEmpleado)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, 'p')
            ->select('p.codigoProgramacionPk')
            ->addSelect('p.codigoPedidoDetalleFk')
            ->addSelect('p.horasDiurnas')
            ->addSelect('p.horasNocturnas')
            ->addSelect('p.anio')
            ->addSelect('p.mes')
            ->addSelect('p.codigoEmpleadoFk')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('ped.codigoClienteFk')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.pedidoRel', 'ped')
            ->leftJoin('ped.clienteRel', 'cl')
            ->where("p.codigoEmpleadoFk = '$codigoEmpleado'")
            ->andWhere("p.anio = '$anio'")
            ->andWhere("p.mes = '$mes'");
        return $queryBuilder->getQuery()->getResult();
    }

    public function validarDiasRetiro($strFechaHasta, $strFechaDesde, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $i = 1;
        $mesFin = intval($strFechaHasta->format('m'));
        $diaFin = intval($strFechaHasta->format('d')) + 1;
        $itnDiasRetiro = 0;
        $conteo = 0;
        while ($i <= 1) {
            if ($conteo > 30) {
                break;
            }
            $ultimoDiaMesFin = cal_days_in_month(CAL_GREGORIAN, $mesFin, $strFechaHasta->format('Y'));
            $strDia = "dia{$diaFin}";
            $conteo++;
            if ($mesFin <= intval($strFechaDesde->format('m')) && $diaFin < intval($strFechaDesde->format('d'))) {
                $arProgramacionDetalle = $em->getRepository(TurProgramacion::class)->findBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'anio' => intval($strFechaHasta->format('Y')), 'mes' => $mesFin, $strDia => 'RET'));
                if ($arProgramacionDetalle) {
                    $itnDiasRetiro++;
                    if ($ultimoDiaMesFin == $diaFin) {
                        $mesFin++;
                        $diaFin = 1;
                    } else {
                        $diaFin++;
                    }
                } else {
                    $i++;
                }
            } else {
                $i++;
            }
        }
        return $itnDiasRetiro;

    }

    public function validacionTurnos($codigoEmpleado, $anio, $mes, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $arrValidacionTurnos = [
                'faltantes' => "",
                'dobles' => "",
            ];
        $queryBuilder = $em->createQueryBuilder()->from(TurProgramacion::class, 'p')
            ->select('p.codigoProgramacionPk')
            ->where('p.codigoEmpleadoFk= ' . $codigoEmpleado)
            ->andWhere('p.anio = ' . $anio)
            ->andWhere('p.mes = ' . $mes);
        for ($i = 1; $i <= 31; $i++) {
            $queryBuilder->addSelect('p.dia' . $i);
        }
        $arrProgramaciones = $queryBuilder->getQuery()->getResult();
        if($arrProgramaciones) {
            $numeroProgramaciones = count($arrProgramaciones);
            for ($j= $fechaDesde; $j<= $fechaHasta; $j++) {
                $turnoFaltante = true;
                $turnoDoble = false;
                $arrTurnos = [];
                for($i = 0; $i < $numeroProgramaciones; $i++) {
                    $turno = $arrProgramaciones[$i]['dia'.$j];
                    if($turno) {
                        if (in_array($turno, $arrTurnos)) {
                            $turnoDoble = true;
                        }
                        $arrTurnos[] = $turno;
                        $turnoFaltante = false;
                    }
                }
                if($turnoFaltante) {
                    $arrValidacionTurnos['faltantes'] .= $j . " ";
                }
                if($turnoDoble) {
                    $arrValidacionTurnos['dobles'] .= $j . " ";
                }

            }
        }
        return $arrValidacionTurnos;
    }

    /**
     * @param \DateTime $fecha
     * @param $recurso
     * @param $tipoFiltro
     * @param FormInterface $form
     * @param $username
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function validarNovedadesRecursoHumano($fecha, $recurso, $tipoFiltro, $form, $username)
    {
        # filtros
        $conVacaciones = $tipoFiltro['vacacion'];
        $conIncapacidades = $tipoFiltro['incapacidad'];
        $conLicencias = $tipoFiltro['licencia'];
        $conIngresos = $tipoFiltro['ingresosRetiros'] ? $form->get("ChkIngresos")->getData() : false;
        $conRetiros = $tipoFiltro['ingresosRetiros'] ? $form->get("ChkRetiros")->getData() : false;

        $session = new Session();
        $session->set("chk-ingresos", $conIngresos);
        $session->set("chk-retiros", $conRetiros);

        $session->set("txt-recurso", $recurso);
        $session->set("fecha-novedad", $fecha);
        $session->set("txt-nombre-recurso", $form->get("txtNombreCorto")->getData());

        $anio = $fecha->format("Y");
        $mes = $fecha->format("m");

        $em = $this->getEntityManager();
        # Limpiamos la tabla.
        $qb = $em->createQueryBuilder();
        $qb->delete(TurNovedadInconsistencia::class, "n")
            ->where("n.usuario='{$username}'");

        if ($tipoFiltro['vacacion']) {
            $qb->andWhere("n.tipo = 'vacacion'");
        } else if ($tipoFiltro["licencia"]) {
            $qb->andWhere("n.tipo = 'licencia'");
        } else if ($tipoFiltro['incapacidad']) {
            $qb->andWhere("n.tipo = 'incapacidad'");
        } else {
            $qb->andWhere("n.tipo = 'ingreso' OR n.tipo = 'retiro'");
        }
        $qb->getQuery()->execute();
        # Construimos la consulta que se encargará de traernos todos los turnos.
        $diasTurnos = [];
        $joins = [];
        for ($i = 1; $i <= 31; $i++) {
            $diasTurnos[] = "SUM(rel_dia{$i}.vacacion) AS es_vacacion_d_{$i}";
            $diasTurnos[] = "SUM(rel_dia{$i}.incapacidad) AS es_incapacidad_d_{$i}";
            $diasTurnos[] = "SUM(rel_dia{$i}.licencia) AS es_licencia_d_{$i}";
            $diasTurnos[] = "SUM(rel_dia{$i}.licencia_no_remunerada) AS es_licencia_nr_d_{$i}";
            $diasTurnos[] = "SUM(rel_dia{$i}.ingreso) AS es_ingreso_d_{$i}";
            $diasTurnos[] = "SUM(rel_dia{$i}.retiro) AS es_retiro_d_{$i}";
            $diasTurnos[] = "SUM(rel_dia{$i}.ausentismo) AS es_ausentismo_d_{$i}";
            $joins[] = "LEFT JOIN tur_turno rel_dia{$i} ON rel_dia{$i}.codigo_turno_pk = p.dia_{$i}";
        }
        $sql = "SELECT p.codigo_empleado_fk, " . implode(', ', $diasTurnos) . " FROM " .
            " tur_programacion p " .
            " " . implode(' ', $joins) . " " .
            " LEFT JOIN rhu_empleado e ON e.codigo_empleado_pk = p.codigo_empleado_fk" .
            " WHERE p.anio = '{$anio}' AND p.mes = '{$mes}'";
        if (!empty($recurso)) {
            $sql .= " AND (e.codigo_empleado_pk = '{$recurso}' OR e.numero_identificacion = '{$recurso}') ";
        } else {
            $session->remove("txt-nombre-recurso");
            $session->remove("txt-recurso");
        }
        $sql .= " GROUP BY p.codigo_empleado_fk ORDER BY p.codigo_empleado_fk ";
        $connection = $em->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();
        $programaciones = $statement->fetchAll();
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $fechaDesde = date_create($fecha->format("Y-m-01"));
        $fechaHasta = date_create($fecha->format("Y-m-t"));
        $mensajesError = [];

        foreach ($programaciones AS $programacion) {
            $diasIncapacidad = 0;
            $diasVacaciones = 0;
            $diasLicencias = 0;
            $diasIngreso = 0;
            $diasIncapacidadRHU = 0;
            $diasVacacionesRHU = 0;
            $diasLicenciasRHU = 0;
            $diasIngresosRHU = 0;
            $diasRetiro = 0;
            $diasRetiroRHU = 0;

            # Si la programación no tiene recurso.
            if ($programacion['codigo_empleado_fk'] == "") {
                continue;
            }

            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($programacion['codigo_empleado_fk']);
            $arContrato = $em->getRepository(RhuContrato::class)->findOneBy([
                'codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(),
                'estadoTerminado' => false
            ]);
            # Analizamos los días en programación.
            for ($i = 1; $i <= 31; $i++) {
                if ($conIncapacidades) {
                    $diasIncapacidad += $programacion["es_incapacidad_d_{$i}"] != 0 ? $programacion["es_incapacidad_d_{$i}"] : 0;
                }
                if ($conVacaciones) {
                    $diasVacaciones += $programacion["es_vacacion_d_{$i}"] != 0 ? $programacion["es_vacacion_d_{$i}"] : 0;
                }
                if ($conLicencias) {
                    $diasLicencias += $programacion["es_licencia_d_{$i}"] != 0 ? $programacion["es_licencia_d_{$i}"] : 0;
                    $diasLicencias += $programacion["es_licencia_nr_d_{$i}"] != 0 ? $programacion["es_licencia_nr_d_{$i}"] : 0;
                    $diasLicencias += $programacion["es_ausentismo_d_{$i}"] != 0 ? $programacion["es_ausentismo_d_{$i}"] : 0;
                }
                if ($conIngresos) {
                    $diasIngreso += $programacion["es_ingreso_d_{$i}"] != 0 ? $programacion["es_ingreso_d_{$i}"] : 0;
                }
                if ($conRetiros) {
                    $diasRetiro += $programacion["es_retiro_d_{$i}"] != 0 ? $programacion["es_retiro_d_{$i}"] : 0;
                }
            }

            if ($arContrato == null && $arEmpleado->getCodigoContratoUltimoFk()) {
                $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoUltimoFk());
            }

            # Si definitivamente el recurso no tiene contrato, entonces no continuamos
            # con las validaciones y lo reportamos como error.
            if ($arContrato == null) {
                $mensajesError[] = $arEmpleado->getNumeroIdentificacion() . " Sin contrato";
                continue; # Se detiene la ejecucion del loop.
            }
            $arNovedadInconsistencia = new TurNovedadInconsistencia();
            $arNovedadInconsistencia->setCodigoEmpleado($arEmpleado->getCodigoEmpleadoPK());
            $arNovedadInconsistencia->setNombreCorto($arEmpleado->getNombreCorto());
            $arNovedadInconsistencia->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
            $arNovedadInconsistencia->setUsuario($username);
            $arNovedadInconsistencia->setBloquearImportacion(false);
            $arNovedadInconsistencia->setFechaDesde($fechaDesde);
            $arNovedadInconsistencia->setFechaHasta($fechaHasta);
            $arNovedadInconsistencia->setCodigoContrato($arContrato->getCodigoContratoPk());

            # Analizamos los días en rhu
            if ($conIncapacidades) {
                $this->getDiasRecurso(TurNovedadInconsistencia::TP_INCAPACIDAD, $arEmpleado, $arContrato, $fecha, $diasIncapacidadRHU, $arNovedadInconsistencia);
            }
            if ($conVacaciones) {
                $this->getDiasRecurso(TurNovedadInconsistencia::TP_VACACION, $arEmpleado, $arContrato, $fecha, $diasVacacionesRHU, $arNovedadInconsistencia);
            }
            if ($conLicencias) {
                 $this->getDiasRecurso(TurNovedadInconsistencia::TP_LICENCIA, $arEmpleado, $arContrato, $fecha, $diasLicenciasRHU, $arNovedadInconsistencia);
            }
            if ($conIngresos) {
                $this->getDiasRecurso(TurNovedadInconsistencia::TP_INGRESO, $arEmpleado, $arContrato, $fecha, $diasIngresosRHU, $arNovedadInconsistencia);
            }
            if ($conRetiros) {
                $bloquear = !$this->getDiasRecurso(TurNovedadInconsistencia::TP_RETIRO, $arEmpleado, $arContrato, $fecha, $diasRetiroRHU, $arNovedadInconsistencia);
                $arNovedadInconsistencia->setBloquearImportacion($bloquear);
            }
            if ($conLicencias && ($diasLicencias > 0 || $diasLicenciasRHU > 0) && ($diasLicenciasRHU != $diasLicencias)) {
                $arNovedadInconsistencia->setTipo(TurNovedadInconsistencia::TP_LICENCIA);
                $arNovedadInconsistencia->setDiasProgramacion($diasLicencias);
                $arNovedadInconsistencia->setDiasRHU($diasLicenciasRHU);
                $em->persist($arNovedadInconsistencia);
            }

            if ($conVacaciones && ($diasVacaciones > 0 || $diasVacacionesRHU > 0) && ($diasVacaciones != $diasVacacionesRHU)) {
                $arNovedadInconsistencia->setTipo(TurNovedadInconsistencia::TP_VACACION);
                $arNovedadInconsistencia->setDiasProgramacion($diasVacaciones);
                $arNovedadInconsistencia->setDiasRHU($diasVacacionesRHU);
                $em->persist($arNovedadInconsistencia);
            }

            if ($conIncapacidades && ($diasIncapacidad > 0 || $diasIncapacidadRHU > 0) && ($diasIncapacidadRHU != $diasIncapacidad)) {
                $arNovedadInconsistencia->setTipo(TurNovedadInconsistencia::TP_INCAPACIDAD);
                $arNovedadInconsistencia->setDiasProgramacion($diasIncapacidad);
                $arNovedadInconsistencia->setDiasRHU($diasIncapacidadRHU);
                $em->persist($arNovedadInconsistencia);
            }

            if ($conIngresos && ($diasIngreso > 0 || $diasIngresosRHU > 0) && ($diasIngresosRHU != $diasIngreso)) {
                $arNovedadInconsistencia->setTipo(TurNovedadInconsistencia::TP_INGRESO);
                $arNovedadInconsistencia->setDiasProgramacion($diasIngreso);
                $arNovedadInconsistencia->setDiasRHU($diasIngresosRHU);
                $em->persist($arNovedadInconsistencia);
            }

            if ($conRetiros && ($diasRetiro > 0 || $diasRetiroRHU > 0) && ($diasRetiroRHU != $diasRetiro)) {
                $arNovedadInconsistencia->setTipo(TurNovedadInconsistencia::TP_RETIRO);
                $arNovedadInconsistencia->setDiasProgramacion($diasRetiro);
                $arNovedadInconsistencia->setDiasRHU($diasRetiroRHU);
                $em->persist($arNovedadInconsistencia);
            }
        }
        $em->flush();
        # Validamos si hubo algun error.
        if (count($mensajesError) > 0) {
            Mensajes::error( implode("<br>", $mensajesError));
        }
    }

    /**
     * @param string $tipo
     * @param RhuContrato $arContrato
     * @param \DateTime $fecha Periodo para el cual se esta ejecutando el analisis.
     * @param TurNovedadInconsistencia $arNovedadInconsistencia
     * @var $arRecurso TurRecurso
     */
    private function getDiasRecurso($tipo, $arEmpleado, $arContrato, $fecha, &$dias, &$arNovedadInconsistencia)
    {
        $em = $this->getEntityManager();
        # Si no hay contrato impedimos que la función continue.
        if ($arContrato == null && $tipo != TurNovedadInconsistencia::TP_RETIRO) {
            return false;
        }

        $fechaIngreso = $arContrato->getFechaDesde();
        $fechaDesde = date_create($fecha->format("Y-m-01"));
        $fechaHasta = date_create($fecha->format("Y-m-t"));
        if ($tipo == TurNovedadInconsistencia::TP_INCAPACIDAD) {
            $intDiasIncapacidad = intval($em->getRepository(RhuIncapacidad::class)->diasIncapacidadPeriodo31($fechaDesde, $fechaHasta, $arEmpleado->getCodigoEmpleadoPk()));
            $dias += $intDiasIncapacidad;
        } else if ($tipo == TurNovedadInconsistencia::TP_LICENCIA) {
            $intDiasLicencia = intval($em->getRepository(RhuLicencia::class)->diasLicenciaPeriodo31($fechaDesde, $fechaHasta, $arEmpleado->getCodigoEmpleadoPk()));
            $dias += $intDiasLicencia;
        } else if ($tipo == TurNovedadInconsistencia::TP_VACACION) {
            $arrVacaciones = $em->getRepository(RhuVacacion::class)->dias($arEmpleado->getCodigoEmpleadoPk(), "", $fechaDesde, $fechaHasta);
            $dias += intval($arrVacaciones['dias']);
        } else if ($tipo == TurNovedadInconsistencia::TP_INGRESO) {
            if ($fechaIngreso == "") {
                return false;
            }
            $intFechaIngreso = strtotime($fechaIngreso->format("Y-m-d"));
            $intFechaActual = strtotime($fecha->format("Y-m-01"));
            if ($intFechaIngreso > $intFechaActual) {
                $fechaA = new \DateTime($fechaIngreso->format("Y-m-d"));
                $fechaB = new \DateTime($fecha->format("Y-m-01"));
                $codigoUltimoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                if ($codigoUltimoContrato) {
                    $arContratoUltimo = $em->getRepository(RhuContrato::class)->find($codigoUltimoContrato);
                    if ($fecha->format("Y-m") == $arContratoUltimo->getFechaHasta()->format("Y-m")) {
                        $diaInicio = (string)($arContratoUltimo->getFechaHasta()->format("d") + 1);
                        $diaInicio = $diaInicio > 31 ? 31 : $diaInicio;
                        $fechaB = new \DateTime($fecha->format("Y-m-{$diaInicio}"));
                        $arNovedadInconsistencia->setFechaDesde($fechaB);
                    }
                }
                $dias += intval($fechaA->diff($fechaB)->format("%a"));
            }
        } else if ($tipo == TurNovedadInconsistencia::TP_RETIRO && ($arContrato->getEstadoTerminado())) {
            $fechaRetiro = $arContrato->getFechaHasta();
            $mesActual = new \DateTime($fecha->format("Y-m-01"));
            $fechaFinMes = new \DateTime($fecha->format("Y-m-t"));
            $mesAVerificar = intval($fecha->format("m"));
            $mesRetiro = intval($fechaRetiro->format("m"));

            # Solucion error al importar retiros que no corresponden al mes actual.
            if ($mesRetiro > $mesAVerificar) {
                return false;
            }
            if ($fechaRetiro < $mesActual) { # Pendiente validar si el dia del retiro se marca o no
                return false;
            }
            $diferencia = $fechaRetiro->diff($fechaFinMes);
            $dias = intval($diferencia->format("%a"));
        }
        return true;
    }

    /**
     * @param integer $idNovedadInconsistencia
     */
    public function importarNovedad($idNovedadInconsistencia)
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        $arNovedadInconsistencia = $em->getRepository(TurNovedadInconsistencia::class)->find($idNovedadInconsistencia);
        if ($arNovedadInconsistencia) {
            $fechaDesde = $arNovedadInconsistencia->getFechaDesde();
            $fechaHasta = $arNovedadInconsistencia->getFechaHasta();
            $desde = $fechaDesde->format("Y-m-d");
            $hasta = $fechaHasta->format("Y-m-d");
            $mes = $fechaDesde->format("m");
            $anio = $fechaDesde->format("Y");

            $tipo = $arNovedadInconsistencia->getTipo();
            if ($tipo == TurNovedadInconsistencia::TP_INCAPACIDAD) {
                $this->importarIncapacidad($arNovedadInconsistencia, $desde, $hasta, $mes, $anio);
            } else if ($tipo == TurNovedadInconsistencia::TP_VACACION) {
                $this->importarVacacion($arNovedadInconsistencia, $desde, $hasta, $mes, $anio);
            } else if ($tipo == TurNovedadInconsistencia::TP_LICENCIA) {
                $this->importarLicencia($arNovedadInconsistencia, $desde, $hasta, $mes, $anio);
            } else if ($tipo == TurNovedadInconsistencia::TP_INGRESO) {
                $this->importarIngresos($arNovedadInconsistencia, $desde, $hasta, $mes, $anio);
            } else if ($tipo == TurNovedadInconsistencia::TP_RETIRO) {
                $this->importarRetiros($arNovedadInconsistencia, $desde, $hasta, $mes, $anio);
            }
        } else {
            $respuesta = "Ha ocurrido un error con la inconsistencia, porfavor verifique los filtros";
        }
        return $respuesta;
    }

    /**
     * @param TurNovedadInconsistencia $arNovedadInconsistencia
     * @param $desde
     * @param $hasta
     * @param $mes
     * @param $anio
     * @return bool
     */
    private function importarIngresos($arNovedadInconsistencia, $desde, $hasta, $mes, $anio)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
//        $diaBloqueoPeriodoProgramacion = $em->getRepository(TurCierreProgramacionPeriodo::class)->getDiaHastaBloqueo($anio, $mes);
        $turnoIngreso = $arConfiguracion->getTurnoIngreso();

        # Listamos las programaciones
        $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy([
            'codigoEmpleadoFk' => $arNovedadInconsistencia->getCodigoRecursoFk(),
            'mes' => $mes,
            'anio' => $anio,
        ], ['horas' => 'DESC']);

        $primeraFila = true;
        $diaIni = $arNovedadInconsistencia->getFechaDesde()->format("j");
        $diaFin = $arNovedadInconsistencia->getDiasRHU();
        if ($diaIni > $diaFin) {
            $arContrato = $em->getRepository(RhuContrato::class)->find($arNovedadInconsistencia->getCodigoContratoFK());
            $diaFin = $arContrato->getFechaDesde()->format("j") - 1;
        }

        foreach ($arProgramaciones AS $arProgramacion) {
            $horasDiurnas = $arProgramacion->getHorasDiurnas();
            $horasNocturnas = $arProgramacion->getHorasNocturnas();
            $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($arProgramacion->getCodigoPedidoDetalleFk());
            $horasDP = $arPedidoDetalle->getHorasDiurnasProgramadas();
            $horasNP = $arPedidoDetalle->getHorasNocturnasProgramadas();
            for ($i = $diaIni; $i <= $diaFin; $i++) {
                if ($i > $diaBloqueoPeriodoProgramacion) {
                    $codigoTurnoActual = call_user_func_array([$arProgramacion, "getDia{$i}"], []);
                    if ($codigoTurnoActual != "") {
                        $arTurno = $em->getRepository("BrasaTurnoBundle:TurTurno")->find($codigoTurnoActual);
                        if ($arTurno) {
                            $horasNocturnas -= $arTurno->getHorasNocturnas();
                            $horasDiurnas -= $arTurno->getHorasDiurnas();
                            $horasDP -= $arTurno->getHorasDiurnas();
                            $horasNP -= $arTurno->getHorasNocturnas();
                        }
                    }
                    if ($primeraFila) {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [$turnoIngreso]);
                    } else {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [null]);
                    }
                    if ($codigoTurnoActual != $turnoIngreso) {
                        call_user_func_array([$arProgramacion, "setDiaN{$i}"], [$codigoTurnoActual]);
                    }
                }
            }
            $primeraFila = false;

            # Almacenamos la programación.
            $arPedidoDetalle->setHorasDiurnasProgramadas($horasDP);
            $arPedidoDetalle->setHorasNocturnasProgramadas($horasNP);
            $arPedidoDetalle->setHorasProgramadas($horasDP + $horasNP);

            $arProgramacion->setHorasDiurnas($horasDiurnas);
            $arProgramacion->setHorasNocturnas($horasNocturnas);
            $arProgramacion->setHoras($horasDiurnas + $horasNocturnas);

            $em->persist($arPedidoDetalle);
            $em->persist($arProgramacion);
        }
        $em->remove($arNovedadInconsistencia);
        $em->flush();
        return true;
    }

    /**
     * @param \Brasa\TurnoBundle\Entity\TurNovedadInconsistencia $arNovedadInconsistencia
     * @param $desde
     * @param $hasta
     * @param $mes
     * @param $anio
     * @return bool
     */
    private function importarRetiros($arNovedadInconsistencia, $desde, $hasta, $mes, $anio)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
        $diaBloqueoPeriodoProgramacion = $em->getRepository(TurCierreProgramacionPeriodo::class)->getDiaHastaBloqueo($anio, $mes);
        $turnoRetiro = $arConfiguracion->getTurnoRetiro();

        # Listamos las programaciones
        $arProgramaciones = $em->getRepository("BrasaTurnoBundle:TurProgramacionDetalle")->findBy([
            'codigoRecursoFk' => $arNovedadInconsistencia->getCodigoRecursoFk(),
            'mes' => $mes,
            'anio' => $anio,
        ], ['horas' => 'DESC']);

        $primeraFila = true;
        $arContrato = $em->getRepository(RhuContrato::class)->find($arNovedadInconsistencia->getCodigoContratoFK());
        $fechaRetiro = $arContrato->getFechaHasta();
        $arRecurso = $arNovedadInconsistencia->getRecursoRel();
        foreach ($arProgramaciones AS $arProgramacion) {
            $intFechaRet = strtotime($fechaRetiro->format("Y-m-d"));
            $intFechaConsulta = strtotime($desde);
            $intFechaRetHasta = strtotime($arNovedadInconsistencia->getFechaHasta()->format('Y-m-d'));
            $intFechaConsultaHasta = strtotime($hasta);

            $fechaRetiro = $arContrato->getFechaHasta();

            if ($intFechaRet < $intFechaConsulta) {
                $diaIni = $arNovedadInconsistencia->getFechaDesde()->format("j");
            } else {
                $diaIni = intval($arContrato->getFechaHasta()->format('d')) + 1;
            }
            if ($intFechaRetHasta > $intFechaConsultaHasta) {
                $diaFin = $arNovedadInconsistencia->getFechaHasta()->format("j");
            } else {
                $diaFin = intval($arNovedadInconsistencia->getFechaHasta()->format('d'));
            }

            $horasDiurnas = $arProgramacion->getHorasDiurnas();
            $horasNocturnas = $arProgramacion->getHorasNocturnas();
            $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($arProgramacion->getCodigoPedidoDetalleFk());
            $horasDP = $arPedidoDetalle->getHorasDiurnasProgramadas();
            $horasNP = $arPedidoDetalle->getHorasNocturnasProgramadas();

            for ($i = $diaIni; $i <= $diaFin; $i++) {
                if ($i > $diaBloqueoPeriodoProgramacion) {//Si el periodo esta bloqueado no se hace nada
                    $codigoTurnoActual = call_user_func_array([$arProgramacion, "getDia{$i}"], []);
                    if ($turnoRetiro == "") {
                        continue;
                    }
                    if ($codigoTurnoActual != "") {
                        $arTurno = $em->getRepository(TurTurno::class)->find($codigoTurnoActual);
                        if ($arTurno) {
                            $horasNocturnas -= $arTurno->getHorasNocturnas();
                            $horasDiurnas -= $arTurno->getHorasDiurnas();
                            $horasDP -= $arTurno->getHorasDiurnas();
                            $horasNP -= $arTurno->getHorasNocturnas();
                        }
                    }
                    if ($primeraFila) {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [$turnoRetiro]);
                    } else {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [null]);
                    }
                    call_user_func_array([$arProgramacion, "setDiaN{$i}"], [$codigoTurnoActual]);
                }
            }

            $primeraFila = false;
            # Almacenamos la programación.
            $arPedidoDetalle->setHorasDiurnasProgramadas($horasDP);
            $arPedidoDetalle->setHorasNocturnasProgramadas($horasNP);
            $arPedidoDetalle->setHorasProgramadas($horasDP + $horasNP);

            $arProgramacion->setHorasDiurnas($horasDiurnas);
            $arProgramacion->setHorasNocturnas($horasNocturnas);
            $arProgramacion->setHoras($horasDiurnas + $horasNocturnas);
            $em->persist($arPedidoDetalle);
            $em->persist($arProgramacion);
        }
        $em->remove($arNovedadInconsistencia);
        $em->flush();
        return true;
    }

    /**
     * @param TurNovedadInconsistencia $arNovedadInconsistencia
     * @param $desde
     * @param $hasta
     * @param $mes
     * @param $anio
     * @return bool
     */
    private function importarIncapacidad($arNovedadInconsistencia, $desde, $hasta, $mes, $anio)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
        $diaBloqueoPeriodoProgramacion = $em->getRepository(TurCierreProgramacionPeriodo::class)->getDiaHastaBloqueo($anio, $mes);
        $turnoIncapacidad = $arConfiguracion->getTurnoIncapacidad();
        $qb = $em->createQueryBuilder();
        $qb->from("App\Entity\RecursoHumano\RhuIncapacidad", "i")
            ->select("i.fechaDesde")
            ->addSelect("i.fechaHasta")
            ->addSelect("it.tipoNovedadTurno AS turnoIncapacidad")
            ->leftJoin("i.incapacidadTipoRel", "it")
            ->where("(i.fechaDesde >= '{$desde}' AND i.fechaDesde <= '{$hasta}') OR " .
                "(i.fechaHasta >= '{$desde}' AND i.fechaHasta <= '{$hasta}') OR " .
                "('{$desde}' >= i.fechaDesde AND '{$desde}' <= i.fechaHasta)")
            ->andWhere("i.codigoEmpleadoFk = {$arNovedadInconsistencia->getCodigoRecursoFk()}");

        $incapacidades = $qb->getQuery()->getResult();
        if (count($incapacidades) == 0) {
            return false;
        }
        # Recorremos cada una de las incapacidades.
        foreach ($incapacidades AS $incapacidad) {
            # Listamos las programaciones
            $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy([
                'codigoEmpleadoFk' => $arNovedadInconsistencia->getCodigoRecursoFk(),
                'mes' => $mes,
                'anio' => $anio,
            ], ['horas' => 'DESC']);
            # Si no hay programaciones pasamos a la siguiente iteración.
            if (!$arProgramaciones) {
                continue;
            }
            $primeraFila = true;
            foreach ($arProgramaciones AS $arProgramacion) {
                $intFechaInc = strtotime($incapacidad['fechaDesde']->format('Y-m-d'));
                $intFechaConsulta = strtotime($desde);
                $intFechaIncHasta = strtotime($incapacidad['fechaHasta']->format('Y-m-d'));
                $intFechaConsultaHasta = strtotime($hasta);
                if ($intFechaInc < $intFechaConsulta) {
                    $diaIniInc = $arNovedadInconsistencia->getFechaDesde()->format("j");
                } else {
                    $diaIniInc = intval($incapacidad['fechaDesde']->format('d'));
                }
                if ($intFechaIncHasta > $intFechaConsultaHasta) {
                    $diaFinInc = $arNovedadInconsistencia->getFechaHasta()->format("j");
                } else {
                    $diaFinInc = intval($incapacidad['fechaHasta']->format('d'));
                }

                $horasDiurnas = $arProgramacion->getHorasDiurnas();
                $horasNocturnas = $arProgramacion->getHorasNocturnas();
                $arPedidoDetalle = $em->getRepository("BrasaTurnoBundle:TurPedidoDetalle")->find($arProgramacion->getCodigoPedidoDetalleFk());
                $horasDP = $arPedidoDetalle->getHorasDiurnasProgramadas();
                $horasNP = $arPedidoDetalle->getHorasNocturnasProgramadas();

                $turnoInc = $incapacidad['turnoIncapacidad'] == "" ? $turnoIncapacidad : $incapacidad['turnoIncapacidad'];
                for ($i = $diaIniInc; $i <= $diaFinInc; $i++) {
                    $turnoActual = call_user_func_array([$arProgramacion, "getDia{$i}"], []);
                    # Si el turno ya está marcado como incapacidad no lo tocamos o si el periodo esta bloqueado no tocamos el turno actual
                    if ($turnoActual == $turnoInc || $i <= $diaBloqueoPeriodoProgramacion) {
                        continue;
                    }

                    # Asignamos el turno de incapacidad
                    if ($primeraFila) {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [$turnoInc]);
                    }

                    if ($turnoActual != $turnoInc) {
                        call_user_func_array([$arProgramacion, "setDiaN{$i}"], [$turnoActual]);
                    }
                    # Recalculamos las horas
                    if ($turnoActual != null) {
                        $arTurnoActual = $em->getRepository("BrasaTurnoBundle:TurTurno")->find($turnoActual);
                        $horasNocturnas -= $arTurnoActual->getHorasNocturnas() != null ? $arTurnoActual->getHorasNocturnas() : 0;
                        $horasDiurnas -= $arTurnoActual->getHorasDiurnas() != null ? $arTurnoActual->getHorasDiurnas() : 0;
                        $horasDP -= $arTurnoActual->getHorasDiurnas() != null ? $arTurnoActual->getHorasDiurnas() : 0;
                        $horasNP -= $arTurnoActual->getHorasNocturnas() != null ? $arTurnoActual->getHorasNocturnas() : 0;
                    }
                }
                $primeraFila = false;
                # Almacenamos la programación.
                $arPedidoDetalle->setHorasDiurnasProgramadas($horasDP);
                $arPedidoDetalle->setHorasNocturnasProgramadas($horasNP);
                $arPedidoDetalle->setHorasProgramadas($horasDP + $horasNP);

                $arProgramacion->setHorasDiurnas($horasDiurnas);
                $arProgramacion->setHorasNocturnas($horasNocturnas);
                $arProgramacion->setHoras($horasDiurnas + $horasNocturnas);

                $em->persist($arPedidoDetalle);
                $em->persist($arProgramacion);
            }
        }
        $em->remove($arNovedadInconsistencia);
        $em->flush();
        return true;
    }


    /**
     * @param TurNovedadInconsistencia $arNovedadInconsistencia
     * @param $desde
     * @param $hasta
     * @param $mes
     * @param $anio
     * @return bool
     */
    private function importarVacacion($arNovedadInconsistencia, $desde, $hasta, $mes, $anio)
    {

        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
        $diaBloqueoPeriodoProgramacion = $em->getRepository(TurCierreProgramacionPeriodo::class)->getDiaHastaBloqueo($anio, $mes);
        $arContrato = $em->getRepository(RhuContrato::class)->find($arNovedadInconsistencia->getCodigoContratoFK());
        $turnoVacacion = $arConfiguracion->getTurnoVacacion();

        # Encontramos las vacaciones del recurso.
        $query = $em->createQueryBuilder()->from("App\Entity\RecursoHumano\RhuVacacion", "v")->select("v.fechaDesdeDisfrute, v.fechaHastaDisfrute")
//            ->where("v.codigoContratoFk = {$arNovedadInconsistencia->getCodigoContratoFK()}")
            ->andWhere("v.codigoEmpleadoFk = {$arContrato->getCodigoEmpleadoFk()}")
            ->andWhere("v.diasDisfrutados > 0")
            ->andWhere("v.estadoAnulado = 0")
            ->andWhere("((v.fechaDesdeDisfrute BETWEEN '{$desde}' AND '{$hasta}') OR (v.fechaHastaDisfrute BETWEEN '{$desde}' AND '{$hasta}')) 
            OR (v.fechaDesdeDisfrute >= '{$desde}' AND v.fechaDesdeDisfrute <= '{$hasta}') 
            OR (v.fechaHastaDisfrute >= '{$hasta}' AND v.fechaDesdeDisfrute <= '{$desde}')");
        $arrVacaciones = $query->getQuery()->getResult();
        if (count($arrVacaciones) == 0) {
            return false;
        }
        # Recorremos cada una de las vacaciones.
        foreach ($arrVacaciones AS $arrVacacion) {
            # Listamos las programaciones
            $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy([
                'codigoRecursoFk' => $arNovedadInconsistencia->getCodigoRecursoFk(),
                'mes' => $mes,
                'anio' => $anio,
            ], ['horas' => 'DESC']);

            $primeraFila = true;

            # Si no hay programaciones pasamos a la siguiente iteración.
            if (!$arProgramaciones) {
                continue;
            }

            foreach ($arProgramaciones AS $arProgramacion) {

                $intFechaVacacion = strtotime($arrVacacion['fechaDesdeDisfrute']->format('Y-m-d'));
                $intFechaConsulta = strtotime($desde);
                $intFechaVacacionHasta = strtotime($arrVacacion['fechaHastaDisfrute']->format('Y-m-d'));
                $intFechaConsultaHasta = strtotime($hasta);
                if ($intFechaVacacion < $intFechaConsulta) {
                    $diaIniVac = $arNovedadInconsistencia->getFechaDesde()->format("j");
                } else {
                    $diaIniVac = intval($arrVacacion['fechaDesdeDisfrute']->format('d'));
                }
                if ($intFechaVacacionHasta > $intFechaConsultaHasta) {
                    $diaFinVac = $arNovedadInconsistencia->getFechaHasta()->format("j");
                } else {
                    $diaFinVac = intval($arrVacacion['fechaHastaDisfrute']->format('d'));
                }

                $horasDiurnas = $arProgramacion->getHorasDiurnas();
                $horasNocturnas = $arProgramacion->getHorasNocturnas();
                $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($arProgramacion->getCodigoPedidoDetalleFk());
                $horasDP = $arPedidoDetalle->getHorasDiurnasProgramadas();
                $horasNP = $arPedidoDetalle->getHorasNocturnasProgramadas();

                for ($i = $diaIniVac; $i <= $diaFinVac; $i++) {
                    $turnoActual = call_user_func_array([$arProgramacion, "getDia{$i}"], []);
                    # Si el turno ya está marcado como incapacidad no lo tocamos o si el periodo esta bloqueado no tocamos el turno
                    if ($turnoActual == $turnoVacacion || $i <= $diaBloqueoPeriodoProgramacion) {
                        continue;
                    }

                    if ($primeraFila) {
                        # Asignamos el turno de incapacidad
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [$turnoVacacion]);
                    } else {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [null]);
                    }

                    if ($turnoActual != $turnoVacacion) {
                        call_user_func_array([$arProgramacion, "setDiaN{$i}"], [$turnoActual]);
                    }

                    # Recalculamos las horas
                    if ($turnoActual != null) {
                        $arTurnoActual = $em->getRepository("BrasaTurnoBundle:TurTurno")->find($turnoActual);
                        $horasNocturnas -= $arTurnoActual->getHorasNocturnas();
                        $horasDiurnas -= $arTurnoActual->getHorasDiurnas();
                        $horasDP -= $arTurnoActual->getHorasDiurnas();
                        $horasNP -= $arTurnoActual->getHorasNocturnas();
                    }
                }

                $primeraFila = false;

                # Almacenamos la programación.
                $arPedidoDetalle->setHorasDiurnasProgramadas($horasDP);
                $arPedidoDetalle->setHorasNocturnasProgramadas($horasNP);
                $arPedidoDetalle->setHorasProgramadas($horasDP + $horasNP);

                $arProgramacion->setHorasDiurnas($horasDiurnas);
                $arProgramacion->setHorasNocturnas($horasNocturnas);
                $arProgramacion->setHoras($horasDiurnas + $horasNocturnas);

                $em->persist($arPedidoDetalle);
                $em->persist($arProgramacion);
            }
        }
        $em->remove($arNovedadInconsistencia);
        $em->flush();
        return true;
    }

    /**
     * @param TurNovedadInconsistencia $arNovedadInconsistencia
     * @param $desde
     * @param $hasta
     * @param $mes
     * @param $anio
     * @return bool
     */
    private function importarLicencia($arNovedadInconsistencia, $desde, $hasta, $mes, $anio)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
//        $diaBloqueoPeriodoProgramacion = $em->getRepository(TurCierreProgramacionPeriodo::class)->getDiaHastaBloqueo($anio, $mes);
        $arContrato = $em->getRepository(RhuContrato::class)->find($arNovedadInconsistencia->getCodigoContratoFK());
        $turnoLicencia = $arConfiguracion->getTurnoLicencia();  # Esto es para capturar un turno de licencia por defecto.
        # Encontramos las incapacidades del recurso.
        $qb = $em->createQueryBuilder();
        $qb->from("App\Entity\RecursoHumano\RhuLicencia", "i")
            ->select("i.fechaDesde")
            ->addSelect("i.fechaHasta")
            ->addSelect("lt.tipoNovedadTurno AS turnoLicencia")
            ->leftJoin("i.licenciaTipoRel", "lt")
            ->where("i.codigoEmpleadoFk = '{$arContrato->getCodigoEmpleadoFk()}'" .
                " AND (((i.fechaDesde BETWEEN '$desde' AND '$hasta') OR (i.fechaHasta BETWEEN '$desde' AND '$hasta')) "
                . "OR (i.fechaDesde >= '$desde' AND i.fechaDesde <= '$hasta') "
                . "OR (i.fechaHasta >= '$hasta' AND i.fechaDesde <= '$desde')) ");

        $licencias = $qb->getQuery()->getResult();
        if (count($licencias) == 0) {
            return false;
        }

        # Recorremos cada una de las incapacidades.
        foreach ($licencias AS $licencia) {
            # Listamos las programaciones
            $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy([
                'codigoEmpleadoFk' => $arNovedadInconsistencia->getCodigoRecursoFk(),
                'mes' => $mes,
                'anio' => $anio,
            ], ['horas' => 'DESC']);
            # Si no hay programaciones pasamos a la siguiente iteración.
            if (!$arProgramaciones) {
                continue;
            }
            $primeraFila = true;
            foreach ($arProgramaciones AS $arProgramacion) {
                $diaIniVac = intval($licencia['fechaDesde']->format('d'));
                $diaFinVac = intval($licencia['fechaHasta']->format('d'));
                //Validacion para verioficar si el rango de fecha de la licencia es mayor al del periodo actual en programacion
                if ($licencia['fechaDesde']->format('Y-m-d') < $desde) {//Se valida si la fecha de la licencia es menor a la del periodo actual para poner el primer dia del mes
                    $desdeLic = date_create($desde);
                    $fechaInicial = date_create($licencia['fechaDesde']->format('Y') . '-' . $desdeLic->format('m') . '-1');
                    $diaIniVac = intval($fechaInicial->format('d'));
                }
                if ($licencia['fechaHasta']->format('Y-m-d') > $hasta) {//Se valida si la fecha es mayor al periodo actual para poner el ultimo dia del mes
                    $hastaLic = date_create($hasta);
                    $fechaFinal = date_create($licencia['fechaDesde']->format('Y') . '-' . $hastaLic->format('m') . '-' . $hastaLic->format('d'));
                    $diaFinVac = intval($fechaFinal->format('d'));
                }
                $horasDiurnas = $arProgramacion->getHorasDiurnas();
                $horasNocturnas = $arProgramacion->getHorasNocturnas();
                $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($arProgramacion->getCodigoPedidoDetalleFk());
                $horasDP = $arPedidoDetalle->getHorasDiurnasProgramadas();
                $horasNP = $arPedidoDetalle->getHorasNocturnasProgramadas();

                $turnoLic = $licencia['turnoLicencia'] == "" ? $turnoLicencia : $licencia['turnoLicencia'];
                for ($i = $diaIniVac; $i <= $diaFinVac; $i++) {
                    $turnoActual = call_user_func_array([$arProgramacion, "getDia{$i}"], []);
                    # Si el turno ya está marcado como incapacidad no lo tocamos o si el periodo esta bloqueado no tocamos el turno
//                    if ($turnoActual == $turnoLic || $i <= $diaBloqueoPeriodoProgramacion) {
//                        continue;
//                    }
                    if ($turnoActual == $turnoLic ) {
                        continue;
                    }

                    # Asignamos el turno de incapacidad
                    if ($primeraFila) {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [$turnoLic]);
                    } else {
                        call_user_func_array([$arProgramacion, "setDia{$i}"], [null]);
                    }
                    # Con esto evitamos sobreescribir si el turno ya fue ingresado.
                    if ($turnoActual != $turnoLic) {
                        call_user_func_array([$arProgramacion, "setDiaN{$i}"], [$turnoActual]);
                    }
                    # Recalculamos las horas
                    if ($turnoActual != null) {
                        $arTurnoActual = $em->getRepository(TurTurno::class)->find($turnoActual);
                        $horasNocturnas -= $arTurnoActual->getHorasNocturnas();
                        $horasDiurnas -= $arTurnoActual->getHorasDiurnas();
                        $horasDP -= $arTurnoActual->getHorasDiurnas();
                        $horasNP -= $arTurnoActual->getHorasNocturnas();
                    }
                }
                $primeraFila = false;
                # Almacenamos la programación.
                $arPedidoDetalle->setHorasDiurnasProgramadas($horasDP);
                $arPedidoDetalle->setHorasNocturnasProgramadas($horasNP);
                $arPedidoDetalle->setHorasProgramadas($horasDP + $horasNP);


                $arProgramacion->setHorasDiurnas($horasDiurnas);
                $arProgramacion->setHorasNocturnas($horasNocturnas);
                $arProgramacion->setHoras($horasDiurnas + $horasNocturnas);

                $em->persist($arPedidoDetalle);
                $em->persist($arProgramacion);
            }
        }

        $em->remove($arNovedadInconsistencia);
        $em->flush();
        return true;
    }

    public function programacionesEmpleadoFechaInconsistencia($codigoEmpleado, $anio, $mes)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, "p")
            ->select("p.codigoProgramacionPk")
            ->where("p.anio = '{$anio}'")
            ->andWhere("p.mes = '{$mes}'")
            ->andWhere("p.codigoEmpleadoFk = '{$codigoEmpleado}'");
        for ($i = 1; $i <= 31; $i++) {
            $turno = "tDia{$i}";
            $queryBuilder->addSelect("{$turno}.horaDesde AS desdeDia{$i}");
            $queryBuilder->addSelect("{$turno}.horaHasta AS hastaDia{$i}");
            $queryBuilder->addSelect("p.dia{$i} AS turnoDia{$i}");
            $queryBuilder->addSelect("{$turno}.descanso AS turnoDiaDescanso{$i}");
            $queryBuilder->leftJoin(TurTurno::class, "{$turno}", "WITH", "{$turno}.codigoTurnoPk = p.dia{$i}");
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Esta función permite obtener todos los turnos dobles ( un recurso que realice dos turnos el mismo día) de todos los recursos.
     * @param string $anio
     * @param string $mes
     * @param string $ultimoDia
     * @param string $primerDia
     * @param integer $codigoGrupoPagoFk
     * @param integer $codigoClienteFk
     * @return TurProgramacionDetalle[]
     */
    public function EmpleadosTurnosDobles($anio, $mes, $primerDia, $ultimoDia, $codigoGrupo = "", $codigoClienteFk = "", $codigoEmpleado = "")
    {
        $turnos = [];
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, "p")
            ->leftJoin( "p.empleadoRel", 'e')
            ->where("p.anio = '{$anio}'")
            ->andWhere("p.mes = '{$mes}'")
            ->andWhere("p.complementario = 0")
            ->andWhere("p.adicional = 0")
            ->orderBy("p.codigoEmpleadoFk");
        if ($codigoGrupo) {//Validacion cuando se filtre por grupo de pago en el soporte de pago
            $queryBuilder->leftJoin( "e.contratoRel", 'c');
            $queryBuilder->andWhere("c.codigoGrupoFk ={$codigoGrupo}");
        }
        if ($codigoClienteFk) {
            $queryBuilder->andWhere("p.codigoClienteFk ={$codigoClienteFk}");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = {$codigoEmpleado}");
        }

        for ($i = $primerDia; $i <= $ultimoDia; $i++) {
            $queryBuilder->leftJoin(TurTurno::class, "relTurnoDia{$i}", 'WITH', "p.dia{$i} = relTurnoDia{$i}.codigoTurnoPk");
            $turnos[] = "p.dia{$i} AS turnoDia{$i}";
            $turnos[] = "relTurnoDia{$i}.complementario AS dia{$i}EsComplementario";
        }
        $queryBuilder->select("p.codigoProgramacionPk, p.codigoEmpleadoFk, " . implode(", ", $turnos));

        return $queryBuilder->getQuery()->execute();
    }

    public function listaPagoAdicionalPuesto($anio, $mes)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(TurProgramacion::class, "pd")
            ->select("pd.codigoEmpleadoFk")
            ->where("pd.anio = '{$anio}'")
            ->andWhere("pd.mes = '{$mes}'")
            ->andWhere("pd.codigoEmpleadoFk <> ''")
            ->groupBy("pd.codigoEmpleadoFk");
        return $qb->getQuery()->getResult();
    }

    public function listaProgramacionAdicionalPagoPuesto($anio, $mes, $codigoEmpleadoFk)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(TurProgramacion::class, "pd")
            ->select("pd.codigoPuestoFk, pd.dia1, pd.dia2, pd.dia3, pd.dia4, pd.dia5, pd.dia6, pd.dia7, pd.dia8, pd.dia9 
                    , pd.dia10, pd.dia11, pd.dia12, pd.dia13, pd.dia14, pd.dia15, pd.dia16, pd.dia17, pd.dia18, pd.dia19, pd.dia20, pd.dia21
                    , pd.dia22, pd.dia23, pd.dia24, pd.dia25, pd.dia26, pd.dia27, pd.dia28, pd.dia29, pd.dia30, pd.dia31")
            ->where("pd.codigoEmpleadoFk = {$codigoEmpleadoFk}")
            ->andWhere("pd.anio = {$anio}")
            ->andWhere("pd.mes = {$mes}")
            ->andWhere("pd.complementario IS NULL OR pd.complementario = 0");
        $arrProgramacionDetalle = $qb->getQuery()->getResult();
        return $arrProgramacionDetalle;
    }

    public function liquidar($codigoProgramacion)
    {
        $em = $this->getEntityManager();
        $arProgramaciones = $em->getRepository(TurProgramacion::class)->find($codigoProgramacion);
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        foreach ($arProgramaciones as $arProgramacion) {
            $douTotalHorasDiurnas += $arProgramacion->getHorasDiurnas();
            $douTotalHorasNocturnas += $arProgramacion->getHorasNocturnas();
            $douTotalHoras += $arProgramacion->getHoras();
        }
        $arProgramacion->setHoras($douTotalHoras);
        $arProgramacion->setHorasDiurnas($douTotalHorasDiurnas);
        $arProgramacion->setHorasNocturnas($douTotalHorasNocturnas);
        $em->persist($arProgramacion);
        $em->flush();
        return true;
    }
}

