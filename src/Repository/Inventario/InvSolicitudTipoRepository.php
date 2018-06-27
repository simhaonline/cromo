<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvSolicitudTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitudTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvSolicitudTipo','ist')
            ->select('ist.codigoSolicitudTipoPk as ID')
            ->addSelect('ist.nombre AS NOMBRE')
            ->addSelect('ist.consecutivo AS CONSECUTIVO')
            ->orderBy('ist.codigoSolicitudTipoPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}