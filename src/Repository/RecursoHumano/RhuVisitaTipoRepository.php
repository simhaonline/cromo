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
use App\Entity\RecursoHumano\RhuConsecutivo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\RecursoHumano\RhuVisitaTipo;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuVisitaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuVisitaTipo::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoVacacion = null;
        $numero = null;
        $grupo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoVacacion = $filtros['codigoVacacion'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $grupo = $filtros['grupo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuVisita::class, 'v')
            ->select('v.codigoVisitaPk')
            ->addselect('v.fecha')
            ->addSelect('v.numero')
            ->addselect('g.nombre as grupo')
            ->addselect('e.numeroIdentificacion as numeroIdentificacion')
            ->addselect('e.nombreCorto as empleado')
            ->addselect('v.fechaDesdePeriodo')
            ->addselect('v.fechaHastaPeriodo')
            ->addselect('v.fechaDesdeDisfrute')
            ->addselect('v.fechaHastaDisfrute')
            ->addselect('v.fechaInicioLabor')
            ->addselect('v.diasPagados')
            ->addselect('v.diasDisfrutados')
            ->addselect('v.diasDisfrutadosReales')
            ->addselect('v.vrTotal')
            ->addselect('v.estadoAutorizado')
            ->addselect('v.estadoAprobado')
            ->addselect('v.estadoAnulado')
            ->leftJoin('v.grupoRel', 'g')
            ->leftJoin('v.empleadoRel', 'e');
        if ($codigoVacacion) {
            $queryBuilder->andWhere("v.codigoVacacionPk = '{$codigoVacacion}'");
        }
        if ($grupo) {
            $queryBuilder->andWhere("v.codigoGrupoFk = '{$grupo}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("v.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("v.numero = '{$numero}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("v.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("v.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('v.codigoVacacionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }



}