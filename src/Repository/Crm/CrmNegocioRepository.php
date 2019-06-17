<?php


namespace App\Repository\Crm;


use App\Entity\Crm\CrmNegocio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CrmNegocioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmNegocio::class);
    }
}