<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDesembarco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDesembarcoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDesembarco::class);
    }

    public function lista()
    {
        $session = new Session();
        $qb = $this->_em->createQueryBuilder()
            ->select('d.codigoDesembarcoPk')
            ->addSelect('d.codigoDespachoFk')
            ->addSelect('d.codigoGuiaFk')
            ->addSelect('d.fecha')
            ->from(TteDesembarco::class, 'd')
            ->where('d.codigoDesembarcoPk <> 0');
        if ($session->get('filtroTteDesembarcoFiltrarFecha')) {
            if ($session->get('filtroTteDesembarcoFechaDesde')) {
                $qb->andWhere("d.fecha >= '{$session->get('filtroTteDesembarcoFechaDesde')} 00:00:00'");
            }
            if ($session->get('filtroTteDesembarcoFechaHasta')) {
                $qb->andWhere("d.fecha <= '{$session->get('filtroTteDesembarcoFechaHasta')} 23:59:59'");
            }
        }
        return $qb;
    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT
                  ds.codigoDesembarcoPk,
                  ds.codigoGuiaFk,
                  ds.codigoDespachoFk,
                  ds.fecha
        FROM App\Entity\Transporte\TteDesembarco ds
        WHERE ds.codigoGuiaFk = :codigoGuia'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();
    }
}