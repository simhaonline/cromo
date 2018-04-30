<?php

namespace App\Repository\General;

use App\Entity\General\TteConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteConfiguracion::class);
    }

    public function impresionFormato(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.nit, 
        c.digitoVerificacion,
        c.nombre,
        c.direccion,
        c.telefono
        FROM App\Entity\General\GenConfiguracion c 
        WHERE c.codigoConfiguracionPk = :codigoConfiguracion'
        )->setParameter('codigoConfiguracion', 1);
        return $query->getSingleResult();

    }


}