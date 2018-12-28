<?php

namespace App\Repository\General;

use App\Entity\General\GenMovimientoComentario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenMovimientoComentarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenMovimientoComentario::class);
    }

    public function lista($codigoModelo, $codigoMovimiento)
    {
        return $this->_em->createQueryBuilder()->from(GenMovimientoComentario::class, 'mc')
            ->select('mc')
            ->where("mc.codigoModeloFk = '{$codigoModelo}'")
            ->andWhere("mc.codigoMovimientoFk = {$codigoMovimiento}")->getQuery()->execute();
    }
}