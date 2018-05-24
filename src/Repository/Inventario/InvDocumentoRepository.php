<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvDocumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvDocumentoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvDocumento::class);
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvDocumento','id')
            ->select('id.codigoDocumentoPk as ID')
            ->addSelect('id.abreviatura')
            ->addSelect('id.nombre')
            ->addSelect('id.asignarConsecutivoCreacion')
            ->addSelect('id.asignarConsecutivoImpresion')
            ->addSelect('id.consecutivo');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}