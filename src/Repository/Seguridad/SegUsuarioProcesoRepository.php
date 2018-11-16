<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\SegUsuarioModelo;
use App\Entity\Seguridad\SegUsuarioProceso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SegUsuarioModelo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SegUsuarioModelo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SegUsuarioModelo[]    findAll()
 * @method SegUsuarioModelo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegUsuarioProcesoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SegUsuarioProceso::class);
    }

}
