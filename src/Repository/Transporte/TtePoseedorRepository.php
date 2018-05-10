<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TtePoseedor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use SoapClient;
class TtePoseedorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TtePoseedor::class);
    }

    public function dqlRndc($codigoPoseedor, $codigoPropietario): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        p.codigoIdentificacionFk,
        p.numeroIdentificacion,
        p.nombre1,
        p.apellido1,
        p.apellido2,
        p.telefono,
        p.movil,
        p.direccion, 
        c.codigoInterface AS codigoCiudad
        FROM App\Entity\Transporte\TtePoseedor p          
        LEFT JOIN p.ciudadRel c     
        WHERE p.codigoPoseedorPk = :codigoPoseedor OR p.codigoPoseedorPk = :codigoPropietario'
        )->setParameter('codigoPoseedor', $codigoPoseedor)
            ->setParameter('codigoPropietario', $codigoPropietario);
        $arTercerosPoseedores =  $query->getResult();
        return $arTercerosPoseedores;

    }

}