<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionCuenta;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuIncidente;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuIncidenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuIncidente::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoIncidente = null;
        $codigoEmpleado = null;
        $incidenteTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoIncidente = $filtros['codigoIncidente'] ?? null;
            $incidenteTipo = $filtros['incidenteTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncidente::class, 'i')
            ->select('i.codigoIncidentePk')
            ->addselect('it.nombre AS tipo')
            ->addSelect('i.fecha')
            ->addselect('e.numeroIdentificacion as numeroIdentificacion')
            ->addselect('e.nombreCorto as empleado')
            ->addselect('c.nombre as cargo')
            ->addSelect('i.nombreReporta')
            ->addSelect('i.fechaNovedad')
            ->addSelect('i.fechaHoraNotificacion')
            ->addSelect('i.fechaHoraCitacionDescargo')
            ->addselect('i.estadoCitado')
            ->addselect('i.estadoAutorizado')
            ->addselect('i.estadoAprobado')
            ->addselect('i.estadoAnulado')
            ->leftJoin('i.incidenteTipoRel', 'it')
            ->leftJoin('i.empleadoRel', 'e')
            ->leftJoin('e.cargoRel', 'c');
        if ($codigoIncidente) {
            $queryBuilder->andWhere("i.codigoIncidentePk = '{$codigoIncidente}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("i.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($incidenteTipo) {
            $queryBuilder->andWhere("i.codigoIncidenteTipoFk = '{$incidenteTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("i.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("i.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('i.codigoIncidentePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }


}