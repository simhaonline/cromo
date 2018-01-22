<?php

namespace App\Repository;

use App\Entity\DespachoRecogida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DespachoRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DespachoRecogida::class);
    }

    public function listaMovimiento(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dr.codigoDespachoRecogidaPk, 
        dr.fecha, 
        dr.codigoOperacionFk,
        dr.codigoRutaRecogidaFk,
        dr.unidades,
        dr.pesoReal,
        dr.pesoVolumen,
        dr.estadoDescargado
        FROM App\Entity\DespachoRecogida dr');
        return $query->execute();

    }
}