<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConceptoHora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuConceptoHoraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConceptoHora::class);
    }

    /**
     * @param $codigoConceptoHora
     * @param $codigoConceptoFk
     */
    public function actualizarConceptoRel($codigoConceptoHora, $codigoConceptoFk)
    {
        $this->_em->createQueryBuilder()
            ->update(RhuConceptoHora::class, 'ch')
            ->set('ch.codigoConceptoFk', '?1')
            ->where("ch.codigoConceptoHoraPk = {$codigoConceptoHora}")
            ->setParameter('1', $codigoConceptoFk)->getQuery()->execute();
    }
}