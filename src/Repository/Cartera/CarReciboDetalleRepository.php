<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarReciboDetalle;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarReciboDetalle::class);
    }

    public function vrPagoRecibo($codigoCuentaCobrar, $id) {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(rd.vrPagoAfectar) as valor FROM App\Entity\Cartera\CarReciboDetalle rd "
            . "WHERE rd.codigoCuentaCobrarFk = " . $codigoCuentaCobrar . " AND rd.codigoReciboFk = " . $id;
        $query = $em->createQuery($dql);
        $vrTotalPago = $query->getSingleScalarResult();
        if (!$vrTotalPago) {
            $vrTotalPago = 0;
        }
        return $vrTotalPago;
    }
}