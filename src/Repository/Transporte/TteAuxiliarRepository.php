<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAuxiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteAuxiliar::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteAuxiliar','au')
            ->select('au.codigoAuxiliarPk AS ID')
            ->addSelect('au.nombreCorto AS NOMBRE_COMPLETO')
            ->addSelect('au.numeroIdentificacion AS IDENTIFICACION');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteAuxiliar::class, 'aux')
            ->select('aux.codigoAuxiliarPk')
            ->addSelect('aux.nombreCorto')
            ->addSelect('aux.numeroIdentificacion')
            ->where('aux.codigoAuxiliarPk <> 0');
        if ($session->get('filtroTteDespachoAuxiliarIdentificacion')) {
            $queryBuilder->andWhere("aux.numeroIdentificacion = '" . $session->get('filtroTteDespachoAuxiliarIdentificacion') . "'");
        }
        if ($session->get('filtroTteDespachoAuxiliar') != "") {
            $queryBuilder->andWhere("aux.nombreCorto LIKE '%" . $session->get('filtroTteDespachoAuxiliar') . "%'");
        };
        return $queryBuilder;
    }
}