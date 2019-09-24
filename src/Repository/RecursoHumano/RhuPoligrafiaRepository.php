<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuAcreditacion;
use App\Entity\RecursoHumano\RhuAcreditacionTipo;
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
use App\Entity\RecursoHumano\RhuPermiso;
use App\Entity\RecursoHumano\RhuPermisoTipo;
use App\Entity\RecursoHumano\RhuPoligrafia;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuPoligrafiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPoligrafia::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoPoligrafia = null;
        $codigoEmpleado = null;
        $poligrafiaTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoPoligrafia = $filtros['codigoPoligrafia'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $poligrafiaTipo = $filtros['poligrafiaTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPoligrafia::class, 'p')
            ->select('p.codigoPoligrafiaPk')
            ->addselect('pt.nombre AS tipo')
            ->addSelect('p.fecha')
            ->addselect('e.numeroIdentificacion as numeroIdentificacion')
            ->addselect('e.nombreCorto as empleado')
            ->addselect('c.nombre as cargo')
            ->addselect('p.estadoAutorizado')
            ->addselect('p.estadoAprobado')
            ->addselect('p.estadoAnulado')
            ->leftJoin('p.poligrafiaTipoRel', 'pt')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('e.cargoRel', 'c');
        if ($codigoPoligrafia) {
            $queryBuilder->andWhere("p.codigoPoligrafiaPk = '{$codigoPoligrafia}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($poligrafiaTipo) {
            $queryBuilder->andWhere("p.codigoPoligrafiaTipoFk = '{$poligrafiaTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("p.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("p.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('p.codigoPoligrafiaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }
}