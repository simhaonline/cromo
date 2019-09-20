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

        $codigoRequisito = null;
        $requisitoTipo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoRequisito = $filtros['codigoRequisito'] ?? null;
            $requisitoTipo = $filtros['requisitoTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
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

        if ($codigoRequisito) {
            $queryBuilder->andWhere("r.codigoRequisitoPk = '{$codigoRequisito}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("c.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($requisitoTipo) {
            $queryBuilder->andWhere("r.codigoRequisitoTipoFk = '{$requisitoTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("r.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("r.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAnulado = 1");
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