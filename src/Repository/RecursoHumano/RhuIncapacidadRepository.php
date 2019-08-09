<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuIncapacidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuIncapacidadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuIncapacidad::class);
    }


    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncapacidad::class, 'i');
        $queryBuilder
            ->select('i.codigoIncapacidadPk')
            ->addSelect('i.numero as numeroEps')
            ->addSelect('es.nombre as entidad')
            ->addSelect('e.numeroIdentificacion as documento')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('it.nombre')
            ->addSelect('i.fechaDesde')
            ->addSelect('i.fechaHasta')
            ->addSelect('i.diasAcumulados')
            ->addSelect('i.diasEntidad')
            ->addSelect('i.diasEmpresa')
            ->addSelect('i.vrCobro')
            ->addSelect('i.vrIncapacidad')
            ->addSelect('i.cantidad')
            ->addSelect('i.diasCobro')
            ->addSelect('i.diasIbcMesAnterior')
            ->addSelect('i.vrIbcMesAnterior')
            ->addSelect('i.estadoCobrar')
            ->addSelect('i.pagarEmpleado')
            ->addSelect('i.estadoProrroga')
            ->addSelect('i.estadoTranscripcion')
            ->addSelect('i.estadoLegalizado')
            ->addSelect('i.codigoUsuario')
            ->leftJoin('i.incapacidadTipoRel', 'it')
            ->leftJoin('i.entidadSaludRel', 'es')
            ->leftJoin('i.empleadoRel', 'e');

        if ($session->get('filtroRhuIncapacidadCodigoEmpleado') != null) {
            $queryBuilder->andWhere("i.codigoEmpleadoFk = '{$session->get('filtroRhuIncapacidadCodigoEmpleado')}'");
        }
        if ($session->get('filtroRhuIncapacidadCodigoEntidadSalud') != null) {
            $queryBuilder->andWhere("i.codigoEntidadSaludFk = '{$session->get('filtroRhuIncapacidadCodigoEntidadSalud')}'");
        }
        if ($session->get('filtroRhuIncapacidadCodigoGrupo') != null) {
            $queryBuilder->andWhere("i.codigoGrupoFk = '{$session->get('filtroRhuIncapacidadCodigoGrupo')}'");
        }
        if ($session->get('filtroRhuIncapacidadNumeroEps') != null) {
            $queryBuilder->andWhere("i.numeroEps = '{$session->get('filtroRhuIncapacidadNumeroEps')}'");
        }
        if ($session->get('filtroRhuIncapacidadLegalizada') != null) {
            $queryBuilder->andWhere("i.estadoLegalizado = '{$session->get('filtroRhuIncapacidadLegalizada')}'");
        }
        if ($session->get('filtroRhuIncapacidadEstadoTranscripcion') != null) {
            $queryBuilder->andWhere("i.estadoTranscripcion = '{$session->get('filtroRhuIncapacidadEstadoTranscripcion')}'");
        }
        if ($session->get('filtroRhuIncapacidadTipoIncapacidad') != null) {
            $queryBuilder->andWhere("i.codigoIncapacidadTipoFk = '{$session->get('filtroRhuIncapacidadTipoIncapacidad')}'");
        }
        if ($session->get('filtroRhuIncapacidadFechaDesde') != null) {
            $queryBuilder->andWhere("i.fechaDesde >= '{$session->get('filtroRhuIncapacidadFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuIncapacidadFechaHasta') != null) {
            $queryBuilder->andWhere("i.fechaHasta <= '{$session->get('filtroRhuIncapacidadFechaHasta')} 23:59:59'");
        }

        return $queryBuilder;
    }

    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoIncapacidad = ""):bool
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncapacidad::class, 'i');
        $dql = "SELECT incapacidad FROM App\Entity\RecursoHumano\RhuIncapacidad incapacidad "
            . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
            . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) "
            . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        if ($codigoIncapacidad != "") {
            $dql = $dql . "AND incapacidad.codigoIncapacidadPk <> " . $codigoIncapacidad;
        }
        $objQuery = $em->createQuery($dql);
        $arIncapacidades = $objQuery->getResult();

        if (count($arIncapacidades) > 0) {
            return FALSE;
        } else {
            return TRUE;
        }

    }
}