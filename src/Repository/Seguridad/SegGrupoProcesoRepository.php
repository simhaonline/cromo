<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\SegGrupoProceso;
use App\Entity\Seguridad\SegUsuarioModelo;
use App\Entity\Seguridad\SegUsuarioProceso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SegUsuarioModelo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SegUsuarioModelo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SegUsuarioModelo[]    findAll()
 * @method SegUsuarioModelo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegGrupoProcesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SegGrupoProceso::class);
    }

}
