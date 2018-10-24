<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuPagoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuPagoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPagoTipo::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuPagoTipo::class,'pt')
            ->select('pt.codigoPagoTipoPk AS ID')
            ->addSelect('pt.nombre')
            ->where('pt.codigoPagoTipoPk IS NOT NULL');
    }
}