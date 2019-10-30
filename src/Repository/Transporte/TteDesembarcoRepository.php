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
        $qb = $this->_em->createQueryBuilder()->from(TteDesembarco::class, 'd')
            ->select('d.codigoDesembarcoPk')
            ->addSelect('d.codigoDespachoFk')
            ->addSelect('d.codigoGuiaFk')
            ->addSelect('d.fecha')
            ->addSelect('d.codigoOperacionOrigenFk')
            ->addSelect('oo.nombre AS origen')
            ->addSelect('d.codigoOperacionDestinoFk')
            ->addSelect('od.nombre AS destino')
            ->leftJoin('d.operacionOrigenRel', 'oo')
            ->leftJoin('d.operacionDestinoRel', 'od')
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

    public function tableroResumen($raw)
    {
        $em = $this->getEntityManager();
        $filtros = $raw['filtros'] ?? null;
        $fechaDesde = null;
        $fechaHasta = null;

        if ($filtros) {
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDesembarco::class, 'd')
            ->select('d.codigoOperacionDestinoFk')
            ->addSelect('od.nombre as operacionDestinoNombre')
            ->addSelect('COUNT(d.codigoGuiaFk) as registros')
            ->addSelect('SUM(g.unidades) as unidades')
            ->addSelect('SUM(g.pesoReal) as pesoReal')
            ->addSelect('SUM(g.pesoVolumen) as pesoVolumen')
            ->addSelect('SUM(g.vrFlete) as vrFlete')
            ->addSelect('SUM(g.vrManejo) as vrManejo')
            ->leftJoin('d.guiaRel', 'g')
            ->leftJoin('d.operacionDestinoRel', 'od')
            ->groupBy('d.codigoOperacionDestinoFk');
        if ($fechaDesde) {
            $queryBuilder->andWhere("d.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("d.fecha <= '{$fechaHasta} 23:59:59'");
        }
        $arrDesembarcos = $queryBuilder->getQuery()->getResult();
        return $arrDesembarcos;
    }
}