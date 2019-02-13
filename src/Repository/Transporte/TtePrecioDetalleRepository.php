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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TtePrecioDetalle::class, 'prd')
            ->select('prd.codigoPrecioDetallePk')
            ->addSelect('co.nombre as ciudadOrigen')
            ->addSelect('prd.codigoCiudadOrigenFk')
            ->addSelect('cd.nombre as ciudadDestino')
            ->addSelect('prd.codigoCiudadDestinoFk')
            ->addSelect('p.nombre')
            ->addSelect('prd.codigoProductoFk')
            ->addSelect('prd.vrPeso')
            ->addSelect('prd.vrUnidad')
            ->addSelect('prd.pesoTope')
            ->addSelect('prd.vrPesoTope')
            ->addSelect('prd.vrPesoTopeAdicional')
            ->addSelect('prd.minimo')
            ->leftJoin('prd.productoRel', 'p')
            ->leftJoin('prd.ciudadDestinoRel','cd')
            ->leftJoin('prd.ciudadOrigenRel','co')
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
