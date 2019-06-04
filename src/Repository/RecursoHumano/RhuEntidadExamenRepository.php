<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEntidadExamen;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEntidadExamenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEntidadExamen::class);
    }

    /**
     * @param $arEntidadExamenes
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($arEntidadExamenes)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuExamenListaPrecio::class, 'ep')
            ->select("COUNT(ep.codigoExamenListaPrecioPk)")
            ->where("ep.codigoEntidadExamenFk = {$arEntidadExamenes} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

}