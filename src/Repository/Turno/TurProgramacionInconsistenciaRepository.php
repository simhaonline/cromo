<?php


namespace App\Repository\Turno;


use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurProgramacionInconsistencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class   TurProgramacionInconsistenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurProgramacionInconsistencia::class);
    }

    public function lista($usuario)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurProgramacionInconsistencia::class, "pi")
            ->select("pi")
            ->where("pi.usuario = '{$usuario}'");
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Esta funcion permite eliminar las inconsistencias asociadas a un usuario
     * @param $nombreUsuario
     * @return integer Total de registros eliminados.
     */
    public function limpiarInconsistenciasUsuario($nombreUsuario)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->delete("App\Entity\Turno\TurProgramacionInconsistencia", "pi")
            ->where("pi.usuario = :nombreUsuario")
            ->setParameter(":nombreUsuario", $nombreUsuario);
        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @param $fechaHasta
     * @param string $codigoRecurso
     * @return TurRecurso[]
     */
    public function getQueryRecursosFechaIngreso($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoGrupo = "")
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->from(RhuEmpleado::class, "e")
            ->select("e")
            ->leftJoin("e.contratoRel", "c")
            ->where("e.estadoContrato = 1 OR (c.fechaDesde <= '{$fechaDesde}' AND c.fechaHasta >= '{$fechaHasta}' AND e.estadoContrato = 0 )")
            ->andWhere('c.habilitadoTurno = 1');
        if ($codigoEmpleado != "") {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$codigoEmpleado}");
        }
        if ($codigoGrupo != "") {
            $queryBuilder->andWhere("c.codigoGrupoFk = '{$codigoGrupo}'");
        }

//        $queryBuilder->andWhere("c.fechaDesde <= '{$fechaHasta}' OR r.fechaIngreso IS NULL")
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Esta funcion permite obtener el total de turnos programados para un recurso por dia.
     * @param $anio
     * @param $mes
     * @param $primerDia
     * @param $ultimoDia
     * @param $codigoRecurso
     * @param $codigoCentroCosto
     * @return mixed
     */
    public function getTotalProgramacionesPorDia($anio, $mes, $primerDia, $ultimoDia, $codigoEmpleado, $codigoGrupo)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurProgramacion::class, "p")
            ->select("p.codigoEmpleadoFk")
            ->addSelect("e.nombreCorto AS nombreEmpleado")
            ->addSelect("e.numeroIdentificacion")
            ->where("p.anio = '{$anio}'")
            ->andWhere("p.mes = '{$mes}'")
            ->andWhere("p.complementario = 0 OR p.complementario IS NULL")
            ->leftJoin("p.empleadoRel", "e")
            ->orderBy("p.codigoEmpleadoFk");
        $queryBuilder->groupBy("p.codigoEmpleadoFk,e.nombreCorto,e.numeroIdentificacion");
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($codigoGrupo) {
            $queryBuilder->andWhere("e.codigoGrupoFk = '{$codigoGrupo}'");
        }
        for ($i = $primerDia; $i <= $ultimoDia; $i++) {
            $queryBuilder->addSelect("SUM(CASE WHEN (p.dia{$i} IS NOT NULL AND NOT COALESCE(p.dia{$i})='') THEN 1 ELSE 0 END) AS dia{$i} ");
        }
        return $queryBuilder->getQuery()->execute();
    }

    /**
     * Esta función permite obtener el listado de recursos con turenos repetidos ( Turnos repetidos, no se tiene en cuenta que realice
     * varios turnos el mismo día, solo si realiza el mismo turno dos veces en el mismo día.
     *
     * @param string $anio
     * @param string $mes
     * @param integer $codigoRecurso
     * @param integer $codigoCentroCosto
     * @return TurProgramacionDetalle[]
     */
    public function getRecursosTurnosRepetidos($anio, $mes, $codigoEmpleado, $codigoGrupo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, "p")
            ->select("p")
            ->leftJoin("p.puestoRel", "pu")
            ->leftJoin("p.empleadoRel", "e")
            ->where("p.anio = '{$anio}'")
            ->andWhere("p.mes='{$mes}'");

        if ($codigoEmpleado) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk ={$codigoEmpleado}");
        }
        if ($codigoGrupo) {
            $queryBuilder->leftJoin('e.contratoRel', 'c');
            $queryBuilder->andWhere("c.codigoGrupoFk ={$codigoGrupo}");
        }

        $queryBuilder->orderBy("p.codigoEmpleadoFk");
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
    public function getRecursosTurnosDobles($anio, $mes, $primerDia, $ultimoDia, $codigoGrupoPagoFk = "", $codigoClienteFk = "", $codigoRecurso = "", $codigoSegmento = "")
    {
        $em = $this->getEntityManager();
        $turnos = [];
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->from("BrasaTurnoBundle:TurProgramacionDetalle", "pd")
            ->leftJoin("BrasaRecursoHumanoBundle:RhuEmpleado", "empleadoRel", 'WITH', "pd.codigoRecursoFk = empleadoRel.codigoEmpleadoPk")
            ->where("pd.anio = '{$anio}'")
            ->andWhere("pd.mes = '{$mes}'")
            ->andWhere("pd.complementario = 0")
            ->andWhere("pd.adicional = 0")
            ->orderBy("pd.codigoRecursoFk");
        if ($codigoGrupoPagoFk != "") {//Validacion cuando se filtre por grupo de pago en el soporte de pago
            $queryBuilder->andWhere("empleadoRel.codigoCentroCostoFk ={$codigoGrupoPagoFk}");
        }
        if ($codigoClienteFk) {
            $queryBuilder->Join("BrasaTurnoBundle:TurPuesto", "puestoRel", 'WITH', "pd.codigoPuestoFk = puestoRel.codigoPuestoPk");
            $queryBuilder->andWhere("puestoRel.codigoClienteFk ={$codigoClienteFk}");
        }
        if ($codigoRecurso != "") {
            $queryBuilder->andWhere("pd.codigoRecursoFk = {$codigoRecurso}");
        }
        if ($codigoSegmento != "") {
            $queryBuilder->andWhere("empleadoRel.codigoSegmentoFk = '{$codigoSegmento}'");
        }

        for ($i = $primerDia; $i <= $ultimoDia; $i++) {
            $queryBuilder->leftJoin("BrasaTurnoBundle:TurTurno", "relTurnoDia{$i}", 'WITH', "pd.dia{$i} = relTurnoDia{$i}.codigoTurnoPk");
            $turnos[] = "pd.dia{$i} AS turnoDia{$i}";
            $turnos[] = "relTurnoDia{$i}.complementario AS dia{$i}EsComplementario";
        }
        $queryBuilder->select("pd.codigoProgramacionDetallePk, pd.codigoRecursoFk, " . implode(", ", $turnos));

        return $queryBuilder->getQuery()->execute();
    }

    public function getRecursosProgramados($anio, $mes, $codigoGrupo = "", $codigoEmpleado = "")
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->from(TurProgramacion::class, "p")
            ->select("e.codigoEmpleadoPk")
            ->addSelect("e.nombreCorto")
            ->addSelect("e.numeroIdentificacion")
            ->leftJoin("p.empleadoRel", "e")
            ->where("p.anio = '{$anio}'")
            ->andWhere("p.mes = '{$mes}'")
            ->groupBy("e.codigoEmpleadoPk")
            ->addGroupBy("e.nombreCorto")
            ->addGroupBy("e.numeroIdentificacion");
        if ($codigoGrupo) {
            $queryBuilder->leftJoin("e.contratoRel", 'c');
            $queryBuilder->andWhere("c.codigoGrupoFk = '{$codigoGrupo}'");
        }
        if ($codigoEmpleado != "") {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = '{$codigoEmpleado}'");
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function getProgramacionesRecurso($codigoRecurso, $anio, $mes)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->from(TurProgramacion::class, "p")
            ->select("p")
            ->where("p.anio = '{$anio}'")
            ->andWhere("p.mes = '{$mes}'")
            ->andWhere("p.codigoEmpleadoFk = '{$codigoRecurso}'");
        return $queryBuilder->getQuery()->getResult();
    }

    public function getProgramacionNovedades($anio, $mes, $codigoRecurso, $codigoGrupo)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->from(TurProgramacion::class, 'p')
            ->select('p')
            ->leftJoin('p.empleadoRel', 'e')
            ->where("p.anio = {$anio} AND p.mes = {$mes}");
        if ($codigoRecurso) {
            $queryBuilder->andWhere("p.codigoRecursoFk = {$codigoRecurso}");
        }
        if ($codigoGrupo) {
            $queryBuilder->leftJoin("e.contratoRel", 'c');
            $queryBuilder->andWhere("c.codigoGrupoFk ={$codigoGrupo}");
        }

        $queryBuilder->orderBy("p.codigoEmpleadoFk");
        return $queryBuilder->getQuery()->getResult();

    }
}