<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoRecogidaAuxiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDespachoRecogidaAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoRecogidaAuxiliar::class);
    }

    public function despacho($codigoDespachoRecogida): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dra.codigoDespachoRecogidaAuxiliarPk, a.numeroIdentificacion, a.nombreCorto 
        FROM App\Entity\Transporte\TteDespachoRecogidaAuxiliar dra 
        LEFT JOIN dra.auxiliarRel a
        WHERE dra.codigoDespachoRecogidaFk = :codigoDespachoRecogida'
        )->setParameter('codigoDespachoRecogida', $codigoDespachoRecogida);

        return $query->execute();
    }

}