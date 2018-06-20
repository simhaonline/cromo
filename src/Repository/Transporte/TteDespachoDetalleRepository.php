<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDespachoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoDetalle::class);
    }

    public function despacho($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dd.codigoDespachoDetallePk, 
        g.numero, 
        g.fechaIngreso,        
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk,     
        dd.unidades,
        dd.pesoReal,
        dd.pesoVolumen,             
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteDespachoDetalle dd 
        LEFT JOIN dd.guiaRel g
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE dd.codigoDespachoFk = :codigoDespacho'
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

    }

    public function guiaCosto($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dd.codigoDespachoDetallePk,  
        dd.vrCostoUnidad,
        dd.vrCostoPeso,
        dd.vrCostoVolumen,
        dd.vrCosto    
        FROM App\Entity\Transporte\TteDespachoDetalle dd
        LEFT JOIN dd.despachoRel d
        WHERE d.estadoAnulado = 0 AND dd.codigoGuiaFk = :guia  
        ORDER BY dd.codigoGuiaFk DESC '
        )->setParameter('guia', $codigoGuia);
        return $query->execute();
    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dd.codigoDespachoDetallePk,
                  dd.codigoDespachoFk,
                  d.numero,
                  dt.nombre AS tipoDespacho,
                  d.fechaSalida,
                  d.estadoGenerado            
        FROM App\Entity\Transporte\TteDespachoDetalle dd 
        LEFT JOIN dd.despachoRel d
        LEFT JOIN d.despachoTipoRel dt
        WHERE dd.codigoGuiaFk = :codigoGuia'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();
    }

}