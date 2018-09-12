<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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
        dd.codigoGuiaFk,
        g.numero,
        g.documentoCliente, 
        g.codigoGuiaTipoFk,
        g.fechaIngreso,        
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk,
        g.nombreDestinatario,     
        dd.unidades,
        dd.pesoReal,
        dd.pesoVolumen,
        dd.vrFlete,
        dd.vrManejo,
        dd.vrCobroEntrega,      
        dd.vrPrecioReexpedicion,       
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteDespachoDetalle dd 
        LEFT JOIN dd.guiaRel g
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE dd.codigoDespachoFk = :codigoDespacho
        ORDER BY cd.nombre ASC '
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
                  d.fechaRegistro,
                  d.numero,
                  cd.nombreCorto AS nombreConductor,
                  cd.movil,
                  vd.placa,
                  dt.nombre AS tipoDespacho,
                  d.fechaSalida,
                  d.estadoAprobado             
        FROM App\Entity\Transporte\TteDespachoDetalle dd 
        LEFT JOIN dd.despachoRel d
        LEFT JOIN d.despachoTipoRel dt
        LEFT JOIN d.conductorRel cd
        LEFT JOIN d.vehiculoRel vd
        WHERE dd.codigoGuiaFk = :codigoGuia'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();
    }

    public function detalle(){

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
            ->select('dd.codigoDespachoDetallePk')
            ->join('dd.guiaRel', 'gd')
            ->join('dd.despachoRel', 'd')
            ->join('gd.clienteRel', 'c')
            ->addSelect('dd.vrCosto AS Costo')
            ->addSelect('d.codigoDespachoPk')
            ->addSelect('c.codigoClientePk')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('dd.unidades')
            ->addSelect('dd.pesoReal')
            ->addSelect('dd.pesoVolumen')
            ->addSelect('dd.vrDeclara')
            ->addSelect('dd.vrFlete')
            ->orderBy('dd.codigoDespachoDetallePk', 'DESC');
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        if($session->get('filtroCodigoDespacho')){
            $queryBuilder->andWhere("d.codigoDespachoPk = {$session->get('filtroCodigoDespacho')}");
        }

        return $queryBuilder;

    }
}