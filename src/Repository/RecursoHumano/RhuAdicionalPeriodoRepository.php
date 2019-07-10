<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuAdicionalPeriodo;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\Transporte\TteMonitoreo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAdicionalPeriodoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAdicionalPeriodo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAdicionalPeriodo::class, 'ap')
            ->select('ap');

        if ($session->get('filtroRhuAdicionalPeriodoEstado') != '') {
            $queryBuilder->andWhere("ap.estadoCerrado = '{$session->get('filtroRhuAdicionalPeriodoEstado')}' ");
        }
        return $queryBuilder;
    }

}