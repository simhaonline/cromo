<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecibo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteReciboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRecibo::class);
    }

    public function relacionCaja($codigoRelacionCaja): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoReciboPk, 
        r.fecha,
        r.vrFlete,
        r.vrManejo,
        r.vrTotal,
        g.fechaIngreso,
        g.codigoGuiaTipoFk,
        g.numero as guiaNumero,
        g.documentoCliente,
        r.codigoGuiaFk,
        c.nombreCorto AS clienteNombre         
        FROM App\Entity\Transporte\TteRecibo r 
        LEFT JOIN r.guiaRel g
        LEFT JOIN r.clienteRel c
        WHERE r.codigoRelacionCajaFk = :codigoRelacionCaja'
        )->setParameter('codigoRelacionCaja', $codigoRelacionCaja);

        return $query->execute();

    }

    public function relacionPendiente(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoReciboPk,
            r.fecha,
            r.vrFlete,
            r.vrManejo,
            r.vrTotal
        FROM App\Entity\Transporte\TteRecibo r 
        WHERE r.estadoRelacion = 0  
        ORDER BY r.codigoReciboPk'
        );
        return $query->execute();
    }

}