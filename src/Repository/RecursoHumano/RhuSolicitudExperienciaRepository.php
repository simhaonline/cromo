<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitudExperiencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuSolicitudExperienciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSolicitudExperiencia::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSolicitudExperiencia','se')
            ->select('se.codigoSolicitudExperienciaPk AS ID')
            ->addSelect('se.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}