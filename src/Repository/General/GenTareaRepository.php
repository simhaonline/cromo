<?php

namespace App\Repository\General;

use App\Entity\General\GenEvento;
use App\Entity\General\GenTarea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenTareaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenTarea::class);
    }
}
