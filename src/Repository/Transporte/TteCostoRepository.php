<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteCostoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteCosto::class);
    }

    public function lista(){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCosto::class, 't')
            ->select('t.codigoCostoPk')
            ->addSelect('t.vrPeso')
            ->addSelect('co.nombre as ciududOrigen')
            ->addSelect('cd.nombre as ciuduadDestino')
            ->leftJoin('t.ciudadDestinoRel', 'cd')
            ->leftJoin('t.ciudadOrigenRel', 'co');

        $queryBuilder->addOrderBy('t.codigoCostoPk', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

}