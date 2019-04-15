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
        dd.pesoCosto,
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
        ORDER BY g.codigoRutaFk ASC, g.ordenRuta ASC, g.codigoCiudadDestinoFk ASC, dd.codigoDespachoDetallePk ASC '
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

    }

    /*public function guiaCosto($codigoGuia): array
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
    }*/

    public function guiaCosto($codigoGuia)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd');
        $queryBuilder
            ->select('SUM(dd.vrCosto) AS vrCosto')
            ->addSelect('SUM(dd.vrCostoPeso) AS vrCostoPeso')
            ->addSelect('SUM(dd.vrCostoVolumen) AS vrCostoVolumen')
            ->addSelect('SUM(dd.vrCostoUnidad) AS vrCostoUnidad')
            ->where("dd.codigoGuiaFk = " . $codigoGuia);
        return $queryBuilder->getQuery()->getSingleResult();
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

    public function detalle()
    {

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
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroCodigoDespacho')) {
            $queryBuilder->andWhere("d.codigoDespachoPk = {$session->get('filtroCodigoDespacho')}");
        }

        return $queryBuilder;

    }

    public function reexpedicion()
    {
        $session = new Session();
        if ($session->get('filtroTtePrecioReexpedicionDespachoCodigo') != '' || $session->get('filtroTtePrecioReexpedicionGuiaCodigo') != '' || $session->get('filtroTtePrecioReexpedicionGuiaNumero') != '') {
            $qb = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
                ->select('dd.codigoDespachoDetallePk')
                ->addSelect('dd.codigoGuiaFk')
                ->addSelect('g.numero')
                ->addSelect('dd.codigoDespachoFk')
                ->addSelect('c.nombreCorto')
                ->addSelect('g.nombreDestinatario')
                ->addSelect('cd.nombre')
                ->addSelect('g.vrDeclara')
                ->addSelect('g.vrFlete')
                ->addSelect('g.vrManejo')
                ->addSelect('dd.vrPrecioReexpedicion')
                ->addSelect('cd.nombre')
                ->leftJoin('dd.guiaRel', 'g')
                ->leftJoin('g.clienteRel', 'c')
                ->leftJoin('g.ciudadDestinoRel', 'cd')
                ->where('dd.codigoDespachoDetallePk <> 0');
            if ($session->get('filtroTtePrecioReexpedicionDespachoCodigo') != '') {
                $qb->andWhere("dd.codigoDespachoFk = {$session->get('filtroTtePrecioReexpedicionDespachoCodigo')}");
            }
            if ($session->get('filtroTtePrecioReexpedicionGuiaNumero') != '') {
                $qb->andWhere("g.numero = {$session->get('filtroTtePrecioReexpedicionGuiaNumero')}");
            }
            if ($session->get('filtroTtePrecioReexpedicionGuiaCodigo') != '') {
                $qb->andWhere("dd.codigoGuiaFk = {$session->get('filtroTtePrecioReexpedicionGuiaCodigo')}");
            }
            $qb->orderBy('dd.codigoDespachoDetallePk', 'ASC');
        } else {
            $qb = [];
        }
        return $qb;
    }

    public function validarGuiasSoporte($codigoDespacho)
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
            ->select('g.estadoSoporte')
            ->addSelect('dd.codigoGuiaFk')
            ->addSelect('g.codigoDespachoFk as codigoDespachoGuiaFk')
            ->leftJoin('dd.guiaRel', 'g')
            ->where('dd.codigoDespachoFk =' . $codigoDespacho);
        return $queryBuilder->getQuery()->execute();
    }

    public function siplatf()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'tdd')
            ->select('tdd.codigoDespachoDetallePk')
            ->addSelect('d.fechaRegistro')
            ->addSelect("CONCAT('3050026', d.numero) as manifiestoDeCarga")
            ->addSelect('co.codigoDivision AS ciudadOrigen')
            ->addSelect('cd.codigoDivision AS ciudadDestino')
            ->addSelect('d.codigoVehiculoFk')
            ->addSelect('p.codigoIdentificacionFk AS codigoIdentificacionPropietario')
            ->addSelect('p.numeroIdentificacion AS identificacionPropietario')
            ->addSelect('p.nombreCorto AS propietario')
            ->addSelect('ps.codigoIdentificacionFk AS codigoIdentificacionPoseedor')
            ->addSelect('ps.numeroIdentificacion AS identificacionPoseedor')
            ->addSelect('ps.nombreCorto AS poseedor')
            ->addSelect('c.codigoIdentificacionFk AS codigoIdentificacionConductor')
            ->addSelect('c.numeroIdentificacion AS identificacionConductor')
            ->addSelect('c.nombreCorto AS conductor')
            ->addSelect('cl.numeroIdentificacion AS identificacionCliente')
            ->addSelect('cl.nombreCorto AS cliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('tdd.vrFlete')
            ->addSelect('tdd.vrFlete AS fleteSinManejo')
            ->addSelect('d.comentario')
            ->addSelect('cd.codigoDivision AS codigoCiudadDestino')
            ->leftJoin('tdd.despachoRel', 'd')
            ->leftJoin('tdd.guiaRel', 'g')
            ->leftJoin('d.ciudadOrigenRel', 'co')
            ->leftJoin('d.ciudadDestinoRel', 'cd')
            ->leftJoin('d.vehiculoRel', 'v')
            ->leftJoin('v.propietarioRel', 'p')
            ->leftJoin('v.poseedorRel', 'ps')
            ->leftJoin('d.conductorRel', 'c')
            ->leftJoin('g.clienteRel', 'cl')
            ->orderBy('tdd.codigoDespachoDetallePk', 'ASC');
        if ($session->get('filtroInvInformeRemisionDetalleCodigoTercero')) {
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroInvInformeRemisionDetalleCodigoTercero')}");
        }
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteDespachoSiplatfFechaDesde') != null) {
            $queryBuilder->andWhere("d.fechaRegistro >= '{$session->get('filtroTteDespachoSiplatfFechaDesde')} 00:00:00'");
        } else {
            $queryBuilder->andWhere("d.fechaRegistro >='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }
        if ($session->get('filtroTteDespachoSiplatfFechaHasta') != null) {
            $queryBuilder->andWhere("d.fechaRegistro <= '{$session->get('filtroTteDespachoSiplatfFechaHasta')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("d.fechaRegistro <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
        }
        return $queryBuilder;
    }
}