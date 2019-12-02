<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCreditoPagoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCreditoPagoTipo::class);
    }
}