<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteNovedad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteNovedadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteNovedad::class);
    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT n.codigoNovedadPk
        FROM App\Entity\Transporte\TteNovedad n 
        WHERE n.codigoGuiaFk = :codigoGuia'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();

    }

}