<?php

namespace App\Repository;

use App\Entity\Guia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GuiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guia::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, g.numero, g.fechaIngreso, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudadOrigen, cd.nombre AS ciudadDestino,
        g.estadoDespachado, g.estadoImpreso, g.estadoEntregado, g.estadoSoporte, g.estadoCumplido, g.comentario
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadOrigenRel co
        LEFT JOIN g.ciudadDestinoRel cd'
        );

        return $query->execute();

    }

    public function despacho($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, g.numero, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudadOrigen, cd.nombre AS ciudadDestino
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadOrigenRel co
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.codigoDespachoFk = :codigoDespacho'
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

    }

    public function despachoPendiente(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, g.numero, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudadOrigen, cd.nombre AS ciudadDestino
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadOrigenRel co
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoDespachado = 0'
        );
        return $query->execute();

    }

}