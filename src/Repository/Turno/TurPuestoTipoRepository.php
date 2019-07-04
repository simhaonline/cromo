<?php


namespace App\Repository\Turno;


use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurPuestoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TurPuestoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPuestoTipo::class);
    }

}