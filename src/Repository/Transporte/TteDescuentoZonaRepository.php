<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDescuentoZona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDescuentoZonaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDescuentoZona::class);
    }

    public function condicion($id){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDescuentoZona::class, 'dz')
            ->select('dz.codigoDescuentoZonaPk')
            ->addSelect('dz.descuento')
            ->addSelect('z.nombre as zonaNombre')
        ->leftJoin('dz.zonaRel', 'z')
        ->where('dz.codigoCondicionFk = ' . $id);
        $arDescuentosZona = $queryBuilder->getQuery()->getResult();
        return $arDescuentosZona;

    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        foreach ($arrSeleccionados as $codigo) {
            $ar = $em->getRepository(TteDescuentoZona::class)->find($codigo);
            if ($ar) {
                $em->remove($ar);
            }
        }
        $em->flush();
    }

}
