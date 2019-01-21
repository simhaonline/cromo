<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipoConcepto;
use App\Entity\Cartera\CarAnticipoDetalle;
use App\Entity\Transporte\CarConfiguracion;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarConfiguracion::class);
    }
}