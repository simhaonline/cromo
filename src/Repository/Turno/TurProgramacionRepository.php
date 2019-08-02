<?php

namespace App\Repository\Turno;


use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmVisita;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurProgramacionRepository extends ServiceEntityRepository
{

    private $horasDiurnasD = 0;
    private $horasNocturnasD = 0;
    private $horasDiurnasP = 0;
    private $horasNocturnasP = 0;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurProgramacion::class);
    }

    public function detalleProgramacion()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('c.nombre as conceptoNombre')
            ->leftJoin('pd.conceptoRel', 'c');
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
                $arProgramacion->setAnio($arPedidoDetalle->getAnio());
                $arProgramacion->setMes($arPedidoDetalle->getMes());
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
                $arrError[] = ["error" => "El recurso {$arPrototipo->getRecursoRel()->getNumeroIdentificacion()} no tiene asignada una secuencia del cliente {$arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto()} en el puesto {$arPedidoDetalle->getPuestoRel()->getNombreCorto()}"];
            }
        }
        if ($arrError == null) {
            $arPedidoDetalle->setHorasProgramadas($totalHorasDiurnasP + $totalHorasNocturnasP);
            $arPedidoDetalle->setHorasDiurnasProgramadas($totalHorasDiurnasP);
            $arPedidoDetalle->setHorasNocturnasProgramadas($totalHorasNocturnasP);
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
        if ($session->get('filtroTurProgramacionAnio') != null) {
            $queryBuilder->andWhere("p.anio = '{$session->get('filtroTurProgramacionAnio')}'");
        }
        if ($session->get('filtroTurProgramacionMes') != null) {
            $queryBuilder->andWhere("p.mes = '{$session->get('filtroTurProgramacionMes')}'");
        }
        if ($session->get('filtroRhuEmpleadoCodigoEmpleado') != null) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = '{$session->get('filtroRhuEmpleadoCodigoEmpleado')}'");
        }
        return $queryBuilder;
    }

    public function periodoDias($anio, $mes, $codigoEmpleado = "")
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurProgramacion::class, 'p')
            ->select('p.codigoProgramacionPk')
            ->addSelect('p.complementario')
            ->addSelect('p.adicional')
            ->where("p.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("p.anio={$anio}")
            ->andWhere("p.mes={$mes}");
        for($i = 1; $i<=31;$i++) {
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
}

