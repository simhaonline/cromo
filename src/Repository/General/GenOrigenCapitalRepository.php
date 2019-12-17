<?php


namespace App\Repository\General;


use App\Entity\General\GenOrigenCapital;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenOrigenCapitalRepository  extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenOrigenCapital::class);
    }
}