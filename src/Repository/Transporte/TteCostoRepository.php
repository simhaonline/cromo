<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

}