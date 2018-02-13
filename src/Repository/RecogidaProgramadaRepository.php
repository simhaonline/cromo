<?php

namespace App\Repository;

use App\Entity\RecogidaProgramada;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RecogidaProgramadaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecogidaProgramada::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT rp.codigoRecogidaProgramadaPk, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad,
            rp.anunciante, rp.hora, rp.codigoOperacionFk, rp.direccion, rp.telefono
        FROM App\Entity\RecogidaProgramada rp 
        LEFT JOIN rp.clienteRel c
        LEFT JOIN rp.ciudadRel co'
        );
        return $query->execute();

    }

}