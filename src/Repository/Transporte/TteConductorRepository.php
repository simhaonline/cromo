<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteConductor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteConductorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteConductor::class);
    }

    public function dqlRndc($codigoConductor): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        c.codigoIdentificacionFk,
        c.numeroIdentificacion,
        c.nombre1,
        c.apellido1,
        c.apellido2,
        c.telefono,
        c.movil,
        c.direccion, 
        c.fechaVenceLicencia,
        c.numeroLicencia,
        c.categoriaLicencia,
        cd.codigoInterface AS codigoCiudad
        FROM App\Entity\Transporte\TteConductor c          
        LEFT JOIN c.ciudadRel cd 
        WHERE c.codigoConductorPk = :codigoConductor'
        )->setParameter('codigoConductor', $codigoConductor);
        $arConductor =  $query->getSingleResult();
        return $arConductor;

    }

    public function dqlRndcManifiesto($codigoConductor): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        c.codigoIdentificacionFk,
        c.numeroIdentificacion
        FROM App\Entity\Transporte\TteConductor c          
        WHERE c.codigoConductorPk = :codigoConductor'
        )->setParameter('codigoConductor', $codigoConductor);
        $arConductor =  $query->getSingleResult();
        return $arConductor;

    }


}