<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvFacturaTipo;
use App\Entity\Inventario\InvOrdenCompraTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvOrdenCompraTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenCompraTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvOrdenCompraTipo','ioct');
        $qb
            ->select('ioct.codigoOrdenCompraTipoPk AS ID')
            ->addSelect('ioct.nombre AS NOMBRE')
            ->addSelect('ioct.consecutivo AS CONSECUTIVO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}