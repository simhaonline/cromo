<?php


namespace App\Repository\Turno;


use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurPuestoAdicional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPuestoAdicionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurPuestoAdicional::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPuestoAdicional::class, 'pa')
            ->select('pa.codigoPuestoAdicionalPk')
            ->addSelect("c.nombre AS concepto")
            ->addSelect("pa.valor")
            ->addSelect('pa.incluirDescanso')
            ->leftJoin('pa.conceptoRel', 'c')
            ->where("pa.codigoPuestoFk = {$id}");
        return $queryBuilder->getQuery()->getResult();
    }

}