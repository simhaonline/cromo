<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSolicitud::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSolicitud','s')
            ->select('s.codigoSolicitudPk AS ID')
            ->addSelect('s.fecha AS FECHA')
            ->addSelect('s.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}