<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSoporteContrato::class);
    }

}
