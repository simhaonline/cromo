<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContratoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurContratoTipo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoTipo::class, 'ct')
            ->select('ct.codigoContratoTipoPk')
            ->addSelect('ct.nombre')
            ->where('ct.codigoContratoTipoPk IS NOT NULL')
            ->orderBy('ct.codigoContratoTipoPk', 'ASC');

        return $queryBuilder;
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Turno\TurContratoTipo', 'ct')
            ->select('ct.codigoContratoTipoPk AS ID')
            ->addSelect('ct.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}
