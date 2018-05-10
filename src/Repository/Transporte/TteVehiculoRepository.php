<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteVehiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteVehiculoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteVehiculo::class);
    }

    public function dqlRndc($codigoVehiculo): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        v.codigoVehiculoPk,
        v.configuracion,
        v.numeroEjes,
        v.codigoMarcaFk as codigoMarca,
        v.codigoLineaFk as codigoLinea,
        v.codigoColorFk as codigoColor,
        v.codigoTipoCombustibleFk as tipoCombustible,
        v.codigoTipoCarroceriaFk as tipoCarroceria,
        v.pesoVacio,
        v.modelo,
        p.codigoIdentificacionFk as tipoIdentificacionPropietario,
        p.numeroIdentificacion as numeroIdentificacionPropietario,
        ps.codigoIdentificacionFk as tipoIdentificacionPoseedor,
        ps.numeroIdentificacion as numeroIdentificacionPoseedor, 
        v.numeroPoliza,
        v.fechaVencePoliza,
        a.numeroIdentificacion as numeroIdentificacionAseguradora,
        a.digitoVerificacion as digitoVerificacionAseguradora,
        v.capacidad       
        FROM App\Entity\Transporte\TteVehiculo v          
        LEFT JOIN v.marcaRel m 
        LEFT JOIN v.propietarioRel p
        LEFT JOIN v.poseedorRel ps
        LEFT JOIN v.aseguradoraRel a 
        WHERE v.codigoVehiculoPk = :codigoVehiculo'
        )->setParameter('codigoVehiculo', $codigoVehiculo);
        $arVehiculo =  $query->getSingleResult();
        return $arVehiculo;

    }

}