<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TtePrecioDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TtePrecioDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TtePrecioDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TtePrecioDetalle::class, 'prd')
            ->select('prd.codigoPrecioDetallePk')
            ->addSelect('p.nombre')
            ->addSelect('prd.vrPeso')
            ->addSelect('prd.vrUnidad')
            ->addSelect('prd.pesoTope')
            ->addSelect('prd.vrPesoTope')
            ->addSelect('prd.vrPesoTopeAdicional')
            ->addSelect('prd.minimo')
            ->leftJoin('prd.productoRel', 'p')
            ->where('prd.codigoPrecioFk = ' . $id )
            ->orderBy('prd.codigoPrecioDetallePk', 'ASC')
            ->getQuery();
        $result = $queryBuilder->getResult();

        return $result;
    }

    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(TtePrecioDetalle::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();
    }

}