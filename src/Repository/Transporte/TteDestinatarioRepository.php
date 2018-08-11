<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDestinatario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDestinatarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDestinatario::class);
    }

}