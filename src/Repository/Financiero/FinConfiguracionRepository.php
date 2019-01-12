<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinConfiguracion;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinConfiguracionRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinConfiguracion::class);
    }

    public function intercambioDatos()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinConfiguracion::class, 'c')
            ->select('c.codigoEmpresaIntercambio')
            ->where('c.codigoConfiguracionPk = 1');

        return $queryBuilder->getQuery()->getSingleResult();
    }


}