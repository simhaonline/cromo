<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurConcepto::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurConcepto::class, 'tc')
            ->select('tc.codigoConceptoPk')
            ->addSelect('tc.nombre')
            ->addSelect('tc.horas')
            ->addSelect('tc.horasDiurnas')
            ->addSelect('tc.horasNocturnas')
            ->orderBy('tc.codigoConceptoPk', 'DESC');
        if ($session->get('filtroTurConceptoNombre') != '') {
            $queryBuilder->andWhere("tc.nombre LIKE '%{$session->get('filtroTurConceptoNombre')}%' ");
        }
        if ($session->get('filtroTurConceptoCodigo') != '') {
            $queryBuilder->andWhere("tc.codigoConceptoPk = {$session->get('filtroTurConceptoCodigo')} ");
        }
        return $queryBuilder->getQuery()->getResult();
    }
}
