<?php

namespace App\Repository\General;

use App\Controller\Estructura\MensajesController;
use App\Entity\General\GenModelo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenModeloRepository extends ServiceEntityRepository
{
    private $excepcionCamposCubo = ['jsonCubo', 'sqlCubo'];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenModelo::class);
    }

}