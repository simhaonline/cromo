<?php

namespace App\Repository;

use App\Entity\Despacho;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DespachoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Despacho::class);
    }

    public function listaMovimiento(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT d.codigoDespachoPk, d.numero, co.nombre AS ciudadOrigen, cd.nombre AS ciudadDestino,
        d.unidades,
        d.pesoReal,
        d.pesoVolumen,
        d.vrFlete,
        d.vrManejo,
        d.vrDeclara
        FROM App\Entity\Despacho d         
        LEFT JOIN d.ciudadOrigenRel co
        LEFT JOIN d.ciudadDestinoRel cd'
        );
        return $query->execute();

    }

}