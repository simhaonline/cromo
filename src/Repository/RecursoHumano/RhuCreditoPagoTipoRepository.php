<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCreditoPagoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCreditoPagoTipo::class);
    }
}