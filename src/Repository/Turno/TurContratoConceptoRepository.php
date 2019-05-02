<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContratoConcepto;
use App\Entity\Turno\TurContratoModalidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurContratoConcepto::class);
    }

}
