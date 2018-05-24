<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitudMotivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuSolicitudMotivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSolicitudMotivo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSolicitudMotivo','sm')
            ->select('sm.codigoSolicitudMotivoPk AS ID')
        ->addSelect('sm.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}