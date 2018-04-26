<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaOtro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteFacturaOtroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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