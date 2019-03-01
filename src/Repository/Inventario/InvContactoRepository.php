<?php

namespace App\Repository\Inventario;


use App\Entity\Financiero\FinContacto;
use App\Entity\Inventario\InvContacto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvContactoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvContacto::class);
    }
    
}