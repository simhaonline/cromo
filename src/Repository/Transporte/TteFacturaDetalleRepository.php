<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteFacturaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaDetalle::class);
    }

    public function factura($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fd.codigoFacturaDetallePk, 
        fd.codigoGuiaFk,
        g.numero, 
        g.codigoGuiaTipoFk,
        g.fechaIngreso,        
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk,     
        fd.unidades,
        fd.pesoReal,
        fd.pesoVolumen,
        fd.vrFlete,
        fd.vrManejo,                      
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.guiaRel g        
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE fd.codigoFacturaFk = :codigoFactura'
        )->setParameter('codigoFactura', $codigoFactura);

        return $query->execute();

    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fd.codigoFacturaDetallePk,
                  fd.codigoFacturaFk,
                  f.numero,
                  f.fecha,                  
                  ft.nombre AS tipoFactura             
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.facturaRel f
        LEFT JOIN f.facturaTipoRel ft
        WHERE fd.codigoGuiaFk = :codigoGuia ORDER BY f.fecha ASC'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();
    }

}