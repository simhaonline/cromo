<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuLicenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuLicencia::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuLicencia::class, 'l')
            ->select('l.codigoLicenciaPk')
            ->addSelect('l.fecha')
            ->addSelect('lt.nombre as licenciaTipo')
            ->addSelect('es.nombre as entidadSalud')
            ->addSelect('em.nombreCorto as empleado')
            ->addSelect('em.numeroIdentificacion')
            ->addSelect('g.nombre as grupo')
            ->addSelect('l.fechaDesde')
            ->addSelect('l.fechaHasta')
            ->addSelect('l.vrLicencia')
            ->addSelect('l.vrCobro')
            ->addSelect('l.diasIbcMesAnterior')
            ->addSelect('l.vrIbcPropuesto')
            ->addSelect('l.afectaTransporte')
            ->addSelect('l.estadoCobrar')
            ->addSelect('l.pagarEmpleado')
            ->addSelect('l.estadoProrroga')
            ->addSelect('l.estadoTranscripcion')
            ->addSelect('l.codigoUsuario')
            ->addSelect('l.cantidad')
            ->leftJoin('l.licenciaTipoRel', 'lt')
            ->leftJoin('l.entidadSaludRel', 'es')
            ->leftJoin('l.empleadoRel', 'em')
            ->leftJoin('l.grupoRel', 'g');

        if($session->get('filtroRhuLicenciaCodigoEmpleado')){
            $queryBuilder->andWhere("l.codigoEmpleadoFk = {$session->get('filtroRhuLicenciaCodigoEmpleado')}");
        }
        if($session->get('filtroRhuLicenciaLiencenciaTipo')){
            $queryBuilder->andWhere("l.codigoLicenciaTipoFk = '{$session->get('filtroRhuLicenciaLiencenciaTipo')}' ");
        }
        if($session->get('filtroRhuLicenciaCodigoGrupo')){
            $queryBuilder->andWhere("l.codigoGrupoFk = '{$session->get('filtroRhuLicenciaCodigoGrupo')}' ");
        }
        if ($session->get('filtroRhuLicenciaFechaDesde') != null) {
            $queryBuilder->andWhere("l.fechaDesde >= '{$session->get('filtroRhuLicenciaFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuLicenciaFechaHasta') != null) {
            $queryBuilder->andWhere("l.fechaHasta <= '{$session->get('filtroRhuLicenciaFechaHasta')} 23:59:59'");
        }
        $queryBuilder->orderBy('l.codigoLicenciaPk', 'DESC');
        return $queryBuilder;

    }

    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoLicencia = ""):bool
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM App\Entity\RecursoHumano\RhuLicencia licencia "
            . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
            . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) "
            . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        if ($codigoLicencia != "") {
            $dql = $dql . "AND licencia.codigoLicenciaPk <> " . $codigoLicencia;
        }
        $objQuery = $em->createQuery($dql);
        $arLicencias = $objQuery->getResult();
        if (count($arLicencias) > 0) {
            return  FALSE;
        } else {
            return  TRUE;
        }
    }
}