<?php

namespace App\Repository;

use App\Entity\DespachoRecogidaAuxiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DespachoRecogidaAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DespachoRecogidaAuxiliar::class);
    }

    public function despacho($codigoDespachoRecogida): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dra.codigoDespachoRecogidaAuxiliarPk, a.numeroIdentificacion, a.nombreCorto 
        FROM App\Entity\DespachoRecogidaAuxiliar dra 
        LEFT JOIN dra.auxiliarRel a
        WHERE dra.codigoDespachoRecogidaFk = :codigoDespachoRecogida'
        )->setParameter('codigoDespachoRecogida', $codigoDespachoRecogida);

        return $query->execute();
    }

}