<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurHoraTipo;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteHora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteHoraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSoporteHora::class);
    }
}

