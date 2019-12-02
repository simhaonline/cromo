<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDespachoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
        dd.vrDeclara,
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

    public function imprimirDetalles($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
            ->select('dd.codigoDespachoDetallePk')
            ->addSelect('dd.codigoGuiaFk')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.direccionDestinatario')
            ->addSelect('g.codigoProductoFk')
            ->addSelect('g.empaqueReferencia')
            ->addSelect('g.codigoCiudadDestinoFk')
            ->addSelect('g.codigoServicioFk')
            ->leftJoin('dd.guiaRel', 'g')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->orderBy('g.codigoCiudadDestinoFk')
            ->addOrderBy('g.codigoClienteFk')
            ->where('dd.codigoDespachoFk = ' . $codigoDespacho);

        return $queryBuilder->getQuery()->getResult();
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

    public function guia($codigoGuia)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
            ->select('dd.codigoDespachoDetallePk')
            ->addSelect('dd.codigoDespachoFk')
            ->addSelect('d.fechaRegistro')
            ->addSelect('d.numero')
            ->addSelect('cd.nombreCorto AS nombreConductor')
            ->addSelect('cd.movil')
            ->addSelect('vd.placa')
            ->addSelect('dt.nombre AS tipoDespacho')
            ->addSelect('d.fechaSalida')
            ->addSelect('d.estadoAprobado')
            ->leftJoin('dd.despachoRel', 'd')
            ->leftJoin('d.despachoTipoRel', 'dt')
            ->leftJoin('d.conductorRel', 'cd')
            ->leftJoin('d.vehiculoRel', 'vd')
            ->where("dd.codigoGuiaFk = {$codigoGuia}")
            ->orderBy('d.fechaRegistro');

        return $queryBuilder->getQuery()->getResult();
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
            ->addSelect("CONCAT('30050026', d.numero) as manifiestoDeCarga")
            ->addSelect('co.codigoDivision AS ciudadOrigen')
            ->addSelect('cd.codigoDivision AS ciudadDestino')
            ->addSelect('d.codigoVehiculoFk')
            ->addSelect('i.codigoInterface AS codigoIdentificacionPropietario')
            ->addSelect('p.numeroIdentificacion AS identificacionPropietario')
            ->addSelect('p.nombreCorto AS propietario')
            ->addSelect('ips.codigoInterface AS codigoIdentificacionPoseedor')
            ->addSelect('ps.numeroIdentificacion AS identificacionPoseedor')
            ->addSelect('ps.nombreCorto AS poseedor')
            ->addSelect('ico.codigoInterface AS codigoIdentificacionConductor')
            ->addSelect('c.numeroIdentificacion AS identificacionConductor')
            ->addSelect('c.nombreCorto AS conductor')
            ->addSelect('v.placaRemolque AS vacio')
            ->addSelect('v.placaRemolque AS vacio2')
            ->addSelect('v.placaRemolque AS vacio3')
            ->addSelect('v.placaRemolque AS vacio4')
            ->addSelect('v.placaRemolque AS vacio5')
            ->addSelect('icl.codigoInterface AS codigoIdentificacionCliente')
            ->addSelect('cl.numeroIdentificacion AS identificacionCliente')
            ->addSelect('cl.nombreCorto AS cliente')
            ->addSelect('icl.codigoInterface AS codigoIdentificacionRemitente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('tdd.vrFlete')
            ->addSelect('tdd.vrFlete AS fleteSinManejo')
            ->addSelect('d.comentario AS observaciones')
            ->addSelect('cd.codigoDivision AS codigoCiudadDestino')
            ->addSelect('SUM(d.vrFlete) AS fleteTotal')
            ->leftJoin('tdd.despachoRel', 'd')
            ->leftJoin('tdd.guiaRel', 'g')
            ->leftJoin('d.ciudadOrigenRel', 'co')
            ->leftJoin('d.ciudadDestinoRel', 'cd')
            ->leftJoin('d.vehiculoRel', 'v')
            ->leftJoin('v.propietarioRel', 'p')
            ->leftJoin('p.identificacionRel', 'i')
            ->leftJoin('v.poseedorRel', 'ps')
            ->leftJoin('ps.identificacionRel', 'ips')
            ->leftJoin('d.conductorRel', 'c')
            ->leftJoin('c.identificacionRel', 'ico')
            ->leftJoin('g.clienteRel', 'cl')
            ->leftJoin('cl.identificacionRel', 'icl')
            ->having('SUM(fleteTotal) > 30000000')
            ->groupBy('tdd.codigoDespachoDetallePk')
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
        return $queryBuilder->getQuery()->getResult();
    }

    public function tableroDetalle($raw)
    {
        $em = $this->getEntityManager();
        $filtros = $raw['filtros'] ?? null;
        $fechaDesde = null;
        $fechaHasta = null;
        $codigoCiudadOrigenFk = null;
        $codigoCiudadDestinoFk = null;

        if ($filtros) {
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $codigoCiudadOrigenFk = $filtros['codigoCiudadOrigenFk'] ?? null;
            $codigoCiudadDestinoFk = $filtros['codigoCiudadDestinoFk'] ?? null;
        }
        $queryBuilder = $em->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
            ->select('g.codigoCiudadDestinoFk')
            ->addSelect('cd.nombre as ciudadDestinoNombre')
            ->addSelect('COUNT(dd.codigoDespachoDetallePk) as registros')
            ->addSelect('SUM(dd.unidades) as unidades')
            ->addSelect('SUM(dd.vrFlete) as vrFlete')
            ->addSelect('SUM(dd.vrManejo) as vrManejo')
            ->addSelect('SUM(dd.pesoReal) as pesoReal')
            ->addSelect('SUM(dd.pesoVolumen) as pesoVolumen')
            ->leftJoin('dd.despachoRel', 'd')
            ->leftJoin('dd.guiaRel', 'g')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->groupBy('g.codigoCiudadDestinoFk');
        if ($fechaDesde) {
            $queryBuilder->andWhere("d.fechaSalida >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("d.fechaSalida <= '{$fechaHasta} 23:59:59'");
        }
        if ($codigoCiudadOrigenFk) {
            $queryBuilder->andWhere("d.codigoCiudadOrigenFk = {$codigoCiudadOrigenFk}");
        }
        if ($codigoCiudadDestinoFk) {
            $queryBuilder->andWhere("d.codigoCiudadDestinoFk = {$codigoCiudadDestinoFk}");
        }
        $arrGuias = $queryBuilder->getQuery()->getResult();
        return $arrGuias;
    }
}