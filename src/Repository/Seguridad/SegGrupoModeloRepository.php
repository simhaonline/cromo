<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\SegGrupoModelo;
use App\Entity\Seguridad\SegUsuarioModelo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class SegGrupoModeloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SegGrupoModelo::class);
    }

}
