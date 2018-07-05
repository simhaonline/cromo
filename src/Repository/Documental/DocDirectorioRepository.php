<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocRegistroCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocDirectorioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocDirectorio::class);
    }


}