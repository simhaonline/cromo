<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurNovedadInconsistencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurNovedadInconsistenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurNovedadInconsistencia::class);
    }

    public function lista($nombreUsuario,$tipoFiltro){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurNovedadInconsistencia::class, 'n')
            ->select('n.codigoNovedadInconsistenciaPk')
            ->addSelect('n.codigoEmpleado')
            ->addSelect('n.numeroIdentificacion')
            ->addSelect('n.nombreCorto')
            ->addSelect('n.tipo')
            ->addSelect('n.fechaDesde')
            ->addSelect('n.fechaHasta')
            ->addSelect('n.diasProgramacion')
            ->addSelect('n.diasRHU')
            ->addSelect('n.bloquearImportacion')
            ->addSelect('n.codigoContrato')
            ->where("n.usuario='{$nombreUsuario}'");
        if ($tipoFiltro['vacacion']) {
            $queryBuilder->andWhere("n.tipo = 'vacacion'");
        } else if ($tipoFiltro["licencia"]) {
            $queryBuilder->andWhere("n.tipo = 'licencia'");
        } else if ($tipoFiltro['incapacidad']) {
            $queryBuilder->andWhere("n.tipo = 'incapacidad'");
        } else {
            $queryBuilder->andWhere("n.tipo = 'ingreso' OR n.tipo = 'retiro'");
        }
        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * Esta función permite listar las incapacidades de un recurso.
     * @param integer $codigoEmpleado
     * @param integer $codigoContrato
     * @param string $fechaDesde
     * @param string $fechaHasta
     * @return RhuIncapacidad[]
     */
    public function listarIncapacidades($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->from("App\Entity\RecursoHumano\RhuIncapacidad", "i")
            ->select("i")
            ->where("i.codigoContratoFk = {$codigoContrato}")
            ->andWhere("i.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("(i.fechaDesde >= '{$fechaDesde}' AND i.fechaDesde <= '{$fechaHasta}') " .
                " OR (i.fechaHasta >= '{$fechaDesde}' AND i.fechaHasta <= '{$fechaHasta}')" .
                " OR ('{$fechaDesde}' >= i.fechaDesde AND '{$fechaDesde}' <= i.fechaHasta)");
        return $qb->getQuery()->execute();
    }

    /**
     * Esta función permite listar las licencias de un recurso.
     * @param integer $codigoEmpleado
     * @param integer $codigoContrato
     * @param string $fechaDesde
     * @param string $fechaHasta
     * @return RhuLicencia[]
     */
    public function listarLicencia($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->from("App\Entity\RecursoHumano\RhuLicencia", "l")
            ->select("l")
            ->where("l.codigoContratoFk = {$codigoContrato}")
            ->andWhere("l.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("(l.fechaDesde >= '{$fechaDesde}' AND l.fechaDesde <= '{$fechaHasta}') " .
                " OR (l.fechaHasta >= '{$fechaDesde}' AND l.fechaHasta <= '{$fechaHasta}')" .
                " OR ('{$fechaDesde}' >= l.fechaDesde AND '{$fechaDesde}' <= l.fechaHasta)");
        return $qb->getQuery()->execute();
    }

    /**
     * Esta función permite listar las licencias de un recurso.
     * @param integer $codigoEmpleado
     * @param integer $codigoContrato
     * @param string $fechaDesde
     * @param string $fechaHasta
     * @return RhuVacacion[]
     */
    public function listarVacaciones($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->from("App\Entity\RecursoHumano\RhuVacacion", "v")
            ->select("v")
            ->where("v.codigoContratoFk = {$codigoContrato}")
            ->andWhere("v.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("(((v.fechaDesdeDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta'))"
                . "OR (v.fechaDesdeDisfrute >= '$fechaDesde' AND v.fechaDesdeDisfrute <= '$fechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$fechaHasta' AND v.fechaDesdeDisfrute <= '$fechaDesde')) "
                . "AND v.codigoEmpleadoFk = '$codigoEmpleado' AND v.diasDisfrutados > 0 AND v.estadoAnulado = 0");
        return $qb->getQuery()->execute();

    }
}