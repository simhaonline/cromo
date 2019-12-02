<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaOtro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteFacturaOtroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteFacturaOtro::class);
    }

    public function listaFacturaDetalle($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fo.codigoFacturaOtroPk 
        FROM App\Entity\Transporte\TteFacturaOtro fo
        WHERE fo.codigoFacturaFk = :codigoFactura'
        )->setParameter('codigoFactura', $codigoFactura);
        return $query->execute();
    }

}