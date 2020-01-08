<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\SegUsuarioModelo;
use App\Entity\Seguridad\SegUsuarioSegmento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class SegUsuarioSegmentoRepository
 * @package App\Repository\Seguridad
 */
class SegUsuarioSegmentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SegUsuarioSegmento::class);
    }

    public function lista($idUsuario){
        $em=$this->getEntityManager();

        $arSeguridadUsuarioSegmento=$em->createQueryBuilder()
            ->from('App:Seguridad\SegUsuarioSegmento','arsus')
            ->select('arsus.codigoSeguridadUsuarioSegmentoPk')
            ->addSelect('seg.nombre AS segmento')
            ->where("arsus.codigoUsuarioFk='{$idUsuario}'")
            ->leftJoin('arsus.segmentoRel', 'seg')
            ->getQuery()->getResult();

        return $arSeguridadUsuarioSegmento;
    }
}
