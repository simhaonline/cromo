<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaPlanilla;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteFacturaPlanillaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaPlanilla::class);
    }

    public function listaFacturaDetalle($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fp.codigoFacturaPlanillaPk 
        FROM App\Entity\Transporte\TteFacturaPlanilla fp
        WHERE fp.codigoFacturaFk = :codigoFactura'
        )->setParameter('codigoFactura', $codigoFactura);
        return $query->execute();
    }

}