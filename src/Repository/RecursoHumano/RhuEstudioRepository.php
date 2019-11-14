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
use App\Entity\RecursoHumano\RhuEstudio;
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
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuEstudioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEstudio::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoVisita = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoVisita = $filtros['codigoVisita'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuEstudio::class, 'es')
            ->select('es.codigoEstudioPk')
            ->addselect('et.nombre AS tipo')
            ->addSelect('es.fecha')
            ->addselect('e.numeroIdentificacion as numeroIdentificacion')
            ->addselect('e.nombreCorto as empleado')
            ->addselect('c.nombre as cargo')
            ->addSelect('es.fechaInicio')
            ->addSelect('es.fechaTerminacion')
            ->addSelect('es.fechaVencimientoAcreditacion')
            ->addselect('es.estadoAutorizado')
            ->addselect('es.estadoAprobado')
            ->addselect('es.estadoAnulado')
            ->leftJoin('es.estudioTipoRel', 'et')
            ->leftJoin('es.empleadoRel', 'e')
            ->leftJoin('e.cargoRel', 'c');
        if ($codigoVisita) {
            $queryBuilder->andWhere("es.codigoEstudioPk = '{$codigoVisita}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("es.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("es.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("es.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("es.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("es.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("es.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("es.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("es.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("es.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('es.codigoEstudioPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }


}