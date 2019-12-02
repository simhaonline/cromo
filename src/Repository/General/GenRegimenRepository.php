<?php

namespace App\Repository\General;

use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenRegimen;
use App\Entity\General\GenTipoPersona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenRegimenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenRegimen::class);
    }

}