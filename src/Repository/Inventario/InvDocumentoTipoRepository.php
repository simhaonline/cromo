<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvDocumentoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvDocumentoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvDocumentoTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvDocumentoTipo','idt')
            ->select('idt.codigoDocumentoTipoPk as ID')
            ->addSelect('idt.nombre AS NOMBRE')
            ->where('idt.codigoDocumentoTipoPk <> 0')
            ->orderBy('idt.codigoDocumentoTipoPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}