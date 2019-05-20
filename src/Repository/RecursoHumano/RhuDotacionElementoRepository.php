<?php


namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuDotacionElemento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuDotacionElementoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuDotacionElemento::class);
    }

}