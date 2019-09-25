<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAccidenteDetalleParticipanteInvestigacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAccidenteDetalleParticipanteInvestigacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAccidenteDetalleParticipanteInvestigacion::class);
    }

}