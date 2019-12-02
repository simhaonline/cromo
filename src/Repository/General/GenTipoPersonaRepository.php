<?php

namespace App\Repository\General;

use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenTipoPersona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenTipoPersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenTipoPersona::class);
    }

}