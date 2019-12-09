<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinCuenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class FinCentroCostoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinCentroCosto::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoCentroCosto = null;
        $nombre = null;
        $estadoInactivo = null;
        if ($filtros) {
            $codigoCentroCosto = $filtros['codigoCentroCosto'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $estadoInactivo = $filtros['estadoInactivo'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinCentroCosto::class, 'cc')
            ->select('cc.codigoCentroCostoPk')
            ->addSelect('cc.nombre')
            ->addSelect('cc.estadoInactivo');
        if ($codigoCentroCosto) {
            $queryBuilder->andWhere("cc.codigoCentroCostoPk LIKE '%{$codigoCentroCosto}%'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("cc.nombre LIKE '%{$nombre}%'");
        }
        switch ($estadoInactivo) {
            case '0':
                $queryBuilder->andWhere("cc.estadoInactivo = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cc.estadoInactivo = 1");
                break;
        }
        $queryBuilder->addOrderBy('cc.codigoCentroCostoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(FinCentroCosto::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }
}