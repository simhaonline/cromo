<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuCreditoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCredito::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCredito = null;
        $creditoTipo = null;
        $creditoPagoTipo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoPagado = null;
        $estadoSuspendido = null;

        if ($filtros) {
            $codigoCredito = $filtros['codigoCredito'] ?? null;
            $creditoTipo = $filtros['creditoTipo'] ?? null;
            $creditoPagoTipo = $filtros['creditoPagoTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoPagado = $filtros['estadoPagado'] ?? null;
            $estadoSuspendido = $filtros['estadoSuspendido'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCredito::class, 'c')
            ->select('c.codigoCreditoPk')
            ->addSelect('ct.nombre as tipo')
            ->addSelect('cpt.nombre as pagoTipo')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('e.estadoContrato as estadoContrato')
            ->addSelect('g.nombre as grupo')
            ->addSelect('c.fecha')
            ->addSelect('c.vrCredito')
            ->addSelect('c.vrCuota')
            ->addSelect('c.numeroCuotaActual')
            ->addSelect('c.numeroCuotas')
            ->addSelect('c.vrAbonos')
            ->addSelect('c.vrSaldo')
            ->addSelect('c.estadoPagado')
            ->addSelect('c.inactivoPeriodo')
            ->addSelect('c.estadoSuspendido')
            ->leftJoin('c.creditoTipoRel', 'ct')
            ->leftJoin('c.creditoPagoTipoRel', 'cpt')
            ->leftJoin('c.empleadoRel', 'e')
            ->leftJoin('c.grupoRel', 'g');
        if ($codigoCredito) {
            $queryBuilder->andWhere("c.codigoCreditoPk = '{$codigoCredito}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("c.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($creditoTipo) {
            $queryBuilder->andWhere("c.codigoCreditoTipoFk = '{$creditoTipo}'");
        }
        if ($creditoPagoTipo) {
            $queryBuilder->andWhere("c.codigoCreditoPagoTipoFk = '{$creditoPagoTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("c.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("c.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoPagado) {
            case '0':
                $queryBuilder->andWhere("c.estadoPagado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoPagado = 1");
                break;
        }
        switch ($estadoSuspendido) {
            case '0':
                $queryBuilder->andWhere("c.estadoSuspendido = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoSuspendido = 1");
                break;
        }
        $queryBuilder->addOrderBy('c.fecha', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    /**
     * @param $arrSeleccionados array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuCredito::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    public function pendientes($codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCredito::class, 'c')
            ->select('c.codigoCreditoPk')
            ->addSelect('ct.nombre AS tipo')
            ->addSelect('c.vrSaldo')
            ->addSelect('c.vrCuota')
            ->where('c.vrSaldo > 0')
            ->andWhere("c.codigoEmpleadoFk = {$codigoEmpleado}")
            ->leftJoin('c.creditoTipoRel', 'ct');
        return $queryBuilder->getQuery()->getArrayResult();
    }
}