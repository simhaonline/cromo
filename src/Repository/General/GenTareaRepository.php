<?php

namespace App\Repository\General;

use App\Entity\General\GenEvento;
use App\Entity\General\GenTarea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenTareaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenTarea::class);
    }

    public function lista($usuario){
        return $this->_em->createQueryBuilder()->from(GenTarea::class,'t')
            ->select('t.codigoTareaPk')
            ->addSelect('t.titulo')
            ->addSelect('tp.nombre as nombrePrioridad')
            ->addSelect('tp.icono')
            ->addSelect('t.descripcion')
            ->addSelect('t.estadoTerminado')
            ->addSelect('t.fecha')
            ->addSelect('t.codigoTareaPrioridadFk')
            ->addSelect('t.usuarioAsigna')
            ->leftJoin('t.tareaPrioridadRel','tp')
            ->where("t.usuarioAsigna = '{$usuario}'")
            ->orderBy('t.codigoTareaPrioridadFk','ASC')
            ->getQuery()->execute();
    }
}
