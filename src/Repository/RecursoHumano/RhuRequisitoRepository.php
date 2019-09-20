<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisito;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuRequisitoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuRequisito::class);
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCredito = null;
        $creditoTipo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoPagado = null;
        $estadoSuspendido = null;

        if ($filtros) {
            $codigoCredito = $filtros['codigoCredito'] ?? null;
            $creditoTipo = $filtros['creditoTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoPagado = $filtros['estadoPagado'] ?? null;
            $estadoSuspendido = $filtros['estadoSuspendido'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisito::class, 'r')
            ->select('r.codigoRequisitoPk')
            ->addSelect('rt.nombre AS tipo')
            ->addSelect('r.fecha')
            ->addSelect('r.nombreCorto')
            ->addSelect('r.numeroIdentificacion')
            ->addSelect('c.nombre AS cargo')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoAnulado')
            ->leftJoin('r.requisitoTipoRel', 'rt')
            ->leftJoin('r.cargoRel', 'c');

        if ($codigoCredito) {
            $queryBuilder->andWhere("c.codigoCreditoPk = '{$codigoCredito}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("c.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($creditoTipo) {
            $queryBuilder->andWhere("c.codigoCreditoTipoFk = '{$creditoTipo}'");
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
        $queryBuilder->addOrderBy('r.fecha', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrSeleccionados
     * @return string
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(RhuRequisitoDetalle::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }
}