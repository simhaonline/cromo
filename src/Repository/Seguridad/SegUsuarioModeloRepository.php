<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\SegUsuarioModelo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SegUsuarioModelo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SegUsuarioModelo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SegUsuarioModelo[]    findAll()
 * @method SegUsuarioModelo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegUsuarioModeloRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SegUsuarioModelo::class);
    }

    public function lista($idUsuario){
        $em=$this->getEntityManager();

        $arSeguridadUsuarioModelo=$em->createQueryBuilder()
            ->from('App:Seguridad\SegUsuarioModelo','arsum')
            ->join('arsum.modeloRel','gm')
            ->select('arsum.codigoSeguridadUsuarioModeloPk as codigoSeguridad')
            ->addSelect('gm.codigoModuloFk as tipo')
            ->addSelect('arsum.codigoModeloFk as modelo')
            ->addSelect('arsum.lista')
            ->addSelect('arsum.detalle')
            ->addSelect('arsum.nuevo')
            ->addSelect('arsum.autorizar')
            ->addSelect('arsum.aprobar')
            ->addSelect('arsum.anular')
            ->where("arsum.codigoUsuarioFk='{$idUsuario}'")
            ->getQuery()->getResult();

        return $arSeguridadUsuarioModelo;
    }
}
