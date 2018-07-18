<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteClienteCondicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteClienteCondicionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteClienteCondicion::class);
    }

    public function clienteCondicion($id){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteClienteCondicion::class, 'cc')
            ->select('cc.codigoClienteCondicionPk')
            ->join('cc.condicionRel', 'con')
            ->addSelect('con.nombre')
            ->addSelect('con.porcentajeManejo')
            ->addSelect('con.manejoMinimoUnidad')
            ->addSelect('con.manejoMinimoDespacho')
            ->addSelect('con.precioPeso')
            ->addSelect('con.precioUnidad')
            ->addSelect('con.precioAdicional')
            ->addSelect('con.descuentoPeso')
            ->addSelect('con.descuentoUnidad')
            ->addSelect('con.pesoMinimo')
            ->addSelect('con.permiteRecaudo')
            ->addSelect('con.precioGeneral')
            ->addSelect('con.redondearFlete')
            ->addSelect('con.limitarDescuentoReexpedicion')
            ->where('cc.codigoClienteFk = ' . $id )
            ->orderBy('cc.codigoCondicionFk', 'ASC')
            ->getQuery();
        $result = $queryBuilder->getResult();

        return $result;

    }

    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(TteClienteCondicion::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();
    }
}