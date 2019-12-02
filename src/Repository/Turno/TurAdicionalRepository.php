<?php


namespace App\Repository\Turno;


use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Turno\TurAdicional;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurPuestoAdicional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurAdicionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurAdicional::class);
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