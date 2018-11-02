<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuPagoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPagoDetalle::class);
    }


    public function eliminarTodoDetalles($codigoProgramacion){
        $this->_em->createQueryBuilder()->delete(RhuPagoDetalle::class,'pd')
            ->leftJoin('pd.pagoRel','p')
            ->leftJoin('p.programacionDetalleRel','prd')
            ->where("prd.codigoProgramacionFk = {$codigoProgramacion}")->getQuery()->execute();
    }
}