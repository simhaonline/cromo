<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteCostoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCosto::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.codigoCostoPk, 
        c.anio,
        c.mes,
        c.vrCostoPeso,
        c.vrCostoVolumen,
        c.vrCosto,
        c.vrPrecio, 
        c.vrRentabilidad 
        FROM App\Entity\Transporte\TteCosto c 
        ORDER BY c.anio, c.mes DESC '
        );
        return $query->execute();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function listaInforme($anio, $mes)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCosto::class, 'c')
            ->select('c.codigoCostoPk')
            ->addSelect('c.anio')
            ->addSelect('c.mes')
            ->addSelect('c.vrCosto')
            ->addSelect('c.vrCostoPeso')
            ->addSelect('c.vrCostoVolumen')
            ->addSelect('c.vrCostoUnidad')
            ->addSelect('c.vrPrecio')
            ->addSelect('c.vrRentabilidad')
            ->addSelect('c.porcentajeRentabilidad')
            ->addSelect('cl.nombreCorto AS nombreCliente')
            ->addSelect('cd.nombre AS destino')
            ->leftJoin('c.clienteRel', 'cl')
            ->leftJoin('c.ciudadDestinoRel', 'cd')
            ->where('c.anio =' . $anio)
            ->andWhere('c.mes =' . $mes);
        return $queryBuilder;

    }

    /**
     * @return array|\Doctrine\ORM\QueryBuilder|mixed
     */
    public function costosPorMes($tipo)
    {
        $session = new Session();
        $queryBuilder = [];
        if ($session->get('filtroTteInformeCostoMes')) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCosto::class, 'c')
                ->select('c.mes')
                ->addSelect('SUM(c.vrCosto) as vrCosto')
                ->addSelect('SUM(c.vrPrecio) as vrPrecio')
                ->where("c.mes = {$session->get('filtroTteInformeCostoMes')} ")
                ->groupBy('c.mes');
            if ($tipo == 1) {
                $queryBuilder->leftJoin('c.clienteRel', 'cl')
                    ->addSelect('cl.nombreCorto')
                    ->addGroupBy('cl.nombreCorto');
            } else {
                $queryBuilder->leftJoin('c.ciudadDestinoRel', 'cd')
                    ->addSelect('cd.nombre')
                    ->addGroupBy('cd.nombre');
            }
            return $queryBuilder->getQuery()->execute();
        } else {
            return $queryBuilder;
        }

    }
}