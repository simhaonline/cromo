<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\General\GenFormaPago;
use App\Entity\General\GenIdentificacion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteDesembarco;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteRedespacho;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;

class TteGuiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuia::class);
    }

    public function listaDql(): string
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = 'SELECT g.codigoGuiaPk, 
        g.codigoServicioFk,
        g.codigoGuiaTipoFk, 
        g.numero,
        g.numeroFactura,
        g.documentoCliente, 
        g.fechaIngreso,        
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,
        g.vrFlete,
        g.vrManejo,
        g.vrRecaudo,         
        g.estadoImpreso,
        g.estadoEmbarcado,
        g.estadoDespachado, 
        g.estadoEntregado, 
        g.estadoSoporte, 
        g.estadoCumplido
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd WHERE g.codigoGuiaPk <> 0 ';
        if ($session->get('filtroTteCodigoGuiaTipo')) {
            $dql .= " AND g.codigoGuiaTipoFk = '" . $session->get('filtroTteCodigoGuiaTipo') . "'";
        }
        if ($session->get('filtroTteCodigoServicio')) {
            $dql .= " AND g.codigoServicioFk = '" . $session->get('filtroTteCodigoServicio') . "'";
        }
        if ($session->get('filtroTteDocumento') != "") {
            $dql .= " AND g.documentoCliente LIKE '%" . $session->get('filtroTteDocumento') . "%'";
        }
        if ($session->get('filtroTteNumeroGuia') != "") {
            $dql .= " AND g.numero =" . $session->get('filtroTteNumeroGuia');
        }
        $dql .= " ORDER BY g.fechaIngreso DESC";
        return $dql;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.codigoServicioFk')
            ->addSelect('tg.codigoGuiaTipoFk')
            ->addSelect('tg.numero')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('tg.codigoOperacionIngresoFk')
            ->addSelect('tg.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('tg.remitente')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.vrManejo')
            ->addSelect('tg.vrRecaudo')
            ->addSelect('tg.vrDeclara')
            ->addSelect('tg.estadoImpreso')
            ->addSelect('tg.estadoAutorizado')
            ->addSelect('tg.estadoAnulado')
            ->addSelect('tg.estadoAprobado')
            ->addSelect('tg.estadoEmbarcado')
            ->addSelect('tg.estadoDespachado')
            ->addSelect('tg.estadoEntregado')
            ->addSelect('tg.estadoSoporte')
            ->addSelect('tg.estadoCumplido')
            ->addSelect('tg.estadoFacturado')
            ->addSelect('tg.estadoNovedad')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->where('tg.codigoGuiaPk <> 0');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteGuiaRemitente') != "") {
            $queryBuilder->andWhere("tg.remitente LIKE '%" . $session->get('filtroTteGuiaRemitente') . "%'");
        }
        if ($session->get('filtroTteFacturaCodigo')) {
            $queryBuilder->andWhere("tg.codigoFacturaFk = '" . $session->get('filtroTteFacturaCodigo') . "'");
        }
        if ($session->get('filtroTteGuiaCodigoGuiaTipo')) {
            $queryBuilder->andWhere("tg.codigoGuiaTipoFk = '" . $session->get('filtroTteGuiaCodigoGuiaTipo') . "'");
        }
        if ($session->get('filtroTteGuiaCodigoServicio')) {
            $queryBuilder->andWhere("tg.codigoServicioFk = '" . $session->get('filtroTteGuiaCodigoServicio') . "'");
        }
        if ($session->get('filtroTteGuiaDocumento') != "") {
            $queryBuilder->andWhere("tg.documentoCliente LIKE '%" . $session->get('filtroTteGuiaDocumento') . "%'");
        }
        if ($session->get('filtroTteGuiaNumero') != "") {
            $queryBuilder->andWhere("tg.numero = " . $session->get('filtroTteGuiaNumero'));
        }
        if ($session->get('filtroTteGuiaCodigo') != "") {
            $queryBuilder->andWhere("tg.codigoGuiaPk = " . $session->get('filtroTteGuiaCodigo'));
        }
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroTteGuiaOperacion')) {
            $queryBuilder->andWhere("tg.codigoOperacionCargoFk = '" . $session->get('filtroTteGuiaOperacion') . "'");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("tg.fechaIngreso >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("tg.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("tg.fechaIngreso <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("tg.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        switch ($session->get('filtroTteGuiaEstadoFacturado')) {
            case '0':
                $queryBuilder->andWhere("tg.estadoFacturado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("tg.estadoFacturado = 1");
                break;
        }
        $queryBuilder->orderBy('tg.fechaIngreso', 'DESC');
        return $queryBuilder;
    }

    /**
     * @param $arGuia TteGuia
     * @return bool
     */
    public function Anular($arGuia)
    {
        $em = $this->getEntityManager();
        if($arGuia->getEstadoAnulado() == 0) {
            if($arGuia->getEstadoFacturaGenerada() == 0 && $arGuia->getEstadoFacturaExportado() == 0) {
                $arGuia->setEstadoAnulado(1);
                $arGuia->setUnidades(0);
                $arGuia->setPesoFacturado(0);
                $arGuia->setPesoReal(0);
                $arGuia->setPesoVolumen(0);
                $arGuia->setVrAbono(0);
                $arGuia->setVrFlete(0);
                $arGuia->setVrDeclara(0);
                $arGuia->setVrManejo(0);
                $arGuia->setVrRecaudo(0);
                $arGuia->setVrCostoReexpedicion(0);
                $arGuia->setVrCobroEntrega(0);
                $em->persist($arGuia);
                $em->flush();
                Mensajes::success("La guia fue anulada con existo");
            } else {
                Mensajes::error("La guia genero un ingreso por factura o esta facturada al cliente");
            }
        }
        return true;
    }

    /**
     * @param $codigoDespacho
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function listaEntrega($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.estadoNovedad')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.estadoNovedadSolucion')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoDespachoFk = ' . $codigoDespacho)
            ->andWhere('g.estadoDespachado = 1')
            ->andWhere('g.estadoEntregado = 0')
            ->andWhere('g.estadoAnulado = 0');
        $queryBuilder->orderBy('g.codigoGuiaPk', 'DESC');
        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function listaGenerarFactura()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.numero')
            ->addSelect('g.numeroFactura')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrAbono')
            ->addSelect('g.estadoNovedad')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.codigoDespachoFk')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.factura = 1')
            ->andWhere('g.estadoFacturaExportado = 0')
            ->orderBy('g.fechaIngreso', 'ASC')
            ->addOrderBy('g.codigoGuiaTipoFk', 'ASC')
            ->addOrderBy('g.numero', 'ASC');
        if ($session->get('filtroTteDespachoCodigo')) {
            $queryBuilder->andWhere("g.codigoDespachoFk = '" . $session->get('filtroTteDespachoCodigo') . "'");
        }
        if ($session->get('filtroTteGuiaCodigoGuiaTipo')) {
            $queryBuilder->andWhere("g.codigoGuiaTipoFk = '" . $session->get('filtroTteGuiaCodigoGuiaTipo') . "'");
        }
        if ($session->get('filtroTteGuiaCodigoOperacionIngreso')) {
            $queryBuilder->andWhere("g.codigoOperacionIngresoFk = '" . $session->get('filtroTteGuiaCodigoOperacionIngreso') . "'");
        }
        return $queryBuilder;
    }

    /**
     * @param $codigoDespacho
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function listaSoporte($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoDespachoFk = ' . $codigoDespacho)
            ->andWhere('g.estadoEntregado = 1')
            ->andWhere('g.estadoSoporte = 0')
            ->andWhere('g.estadoAnulado = 0');
        $queryBuilder->orderBy('g.codigoGuiaPk', 'DESC');
        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function listaDesembarco()
    {
        $em = $this->_em;
        $session = new Session();
        $queryBuilder = [];
        $id = $session->get('filtroTteDespachoCodigo');
        if ($id) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
            if ($arDespacho) {
                if ($arDespacho->getEstadoAprobado()) {
                    $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
                        ->select('g.codigoGuiaPk')
                        ->addSelect('g.numero')
                        ->addSelect('g.fechaIngreso')
                        ->addSelect('g.codigoOperacionIngresoFk')
                        ->addSelect('g.codigoOperacionCargoFk')
                        ->addSelect('c.nombreCorto AS clienteNombre')
                        ->addSelect('cd.nombre AS ciudadDestino')
                        ->addSelect('g.unidades')
                        ->addSelect('g.pesoReal')
                        ->leftJoin('g.clienteRel', 'c')
                        ->leftJoin('g.ciudadDestinoRel', 'cd')
                        ->where('g.codigoDespachoFk = ' . $id)
                        ->andWhere('g.estadoDespachado = 1')
                        ->andWhere('g.estadoAnulado = 0');
                    $queryBuilder->orderBy('g.codigoGuiaPk', 'DESC');
                } else {
                    Mensajes::error('El despacho no esta aprobado y no se puede desembarcar');
                }
            } else {
                Mensajes::error('El despacho no existe');
            }

        }
        return $queryBuilder;
    }

    /**
     * @param $codigoDespacho
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function despacho($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.nombreDestinatario')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoDespachoFk = ' . $codigoDespacho);
        $queryBuilder->orderBy('g.codigoGuiaPk', 'DESC');

        return $queryBuilder;
    }

    /**
     * @param $codigoDespacho
     * @return mixed
     */
    public function despachoOrden($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.numero')
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
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->orderBy('g.codigoCiudadDestinoFk')
            ->addOrderBy('g.ordenRuta')
            ->where('g.codigoDespachoFk = ' . $codigoDespacho);

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @param $codigoDespacho
     * @return mixed
     */
    public function despachoCobroEntrega($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
            ->select('dd.codigoDespachoDetallePk')
            ->addSelect('dd.codigoGuiaFk')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion AS nit')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.direccionDestinatario')
            ->addSelect('g.codigoProductoFk')
            ->addSelect('g.empaqueReferencia')
            ->addSelect('dd.vrCobroEntrega')
            ->addSelect('dd.vrFlete')
            ->addSelect('dd.vrManejo')
            ->addSelect('g.vrAbono')
            ->addSelect('dd.vrRecaudo')
            ->addSelect('g.factura')
            ->addSelect('gt.generaCobroEntrega')
            ->leftJoin('dd.guiaRel', 'g')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.guiaTipoRel', 'gt')
            ->where('dd.codigoDespachoFk = ' . $codigoDespacho)
            ->andWhere('dd.vrCobroEntrega > 0')
            ->orderBy('g.codigoCiudadDestinoFk')
            ->addOrderBy('g.ordenRuta');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoDespacho
     * @return mixed
     */
    public function relacionEntrega($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaDespacho')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.direccionDestinatario')
            ->addSelect('g.codigoProductoFk')
            ->addSelect('g.empaqueReferencia')
            ->addSelect('g.vrCobroEntrega')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoDespachoFk = ' . $codigoDespacho)
            ->orderBy('g.codigoCiudadDestinoFk')
            ->addOrderBy('g.ordenRuta');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function despachoPendiente($codigoOperacionCargo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.codigoGuiaTipoFk')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('tg.numero')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.codigoOperacionIngresoFk')
            ->addSelect('tg.codigoOperacionCargoFk')
            ->addSelect('tg.unidades')
            ->addSelect('tg.codigoRutaFk')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.nombreDestinatario as destinatario')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->where('tg.estadoDespachado = 0')
            ->andWhere('tg.estadoEmbarcado = 0')
            ->andWhere('tg.estadoImpreso = 1')
            ->andWhere('tg.estadoAnulado = 0')
            ->andWhere("tg.codigoOperacionCargoFk ='" . $codigoOperacionCargo . "'");
        if ($session->get('filtroTteDespachoGuiaNumero') != '') {
            $queryBuilder->andWhere("tg.numero =  {$session->get('filtroTteDespachoGuiaNumero')}");
        }
        if ($session->get('filtroTteDespachoGuiaCodigoGuiaTipo') != '') {
            $queryBuilder->andWhere("tg.codigoGuiaTipoFk =  '{$session->get('filtroTteDespachoGuiaCodigoGuiaTipo')}'");
        }
        if ($session->get('filtroTteDespachoGuiaCodigoRuta') != '') {
            $queryBuilder->andWhere("tg.codigoRutaFk =  '{$session->get('filtroTteDespachoGuiaCodigoRuta')}'");
        }
        $queryBuilder->orderBy('tg.codigoRutaFk, tg.codigoCiudadDestinoFk, tg.ordenRuta', 'ASC');
        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendienteEntrega()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.codigoOperacionIngresoFk')
            ->addSelect('tg.codigoOperacionCargoFk')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('dg.fechaRegistro')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('tg.codigoDespachoFk')
            ->addSelect('(dg.numero) AS manifiesto')
            ->addSelect('ct.movil')
            ->addSelect('ct.nombreCorto AS conductor')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.estadoNovedad')
            ->addSelect('tg.estadoNovedadSolucion')
            ->addSelect('tg.estadoCumplido')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->leftJoin('tg.despachoRel', 'dg')
            ->leftJoin('dg.conductorRel', 'ct')
            ->where('tg.estadoEntregado = 0')
            ->andWhere('tg.estadoDespachado = 1')
            ->andWhere('tg.estadoAnulado = 0');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTtePendienteEntregaFechaDesde') != null) {
            $queryBuilder->andWhere("tg.fechaIngreso >= '{$session->get('filtroTtePendienteEntregaFechaDesde')} 00:00:00'");
        } else {
            $queryBuilder->andWhere("tg.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }
        if ($session->get('filtroTtePendienteEntregaFechaHasta') != null) {
            $queryBuilder->andWhere("tg.fechaIngreso <= '{$session->get('filtroTtePendienteEntregaFechaHasta')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("tg.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
        }
        switch ($session->get('filtroTteGuiaEstadoNovedad')) {
            case '0':
                $queryBuilder->andWhere("tg.estadoNovedad = 0");
                break;
            case '1':
                $queryBuilder->andWhere("tg.estadoNovedad = 1");
                break;
        }
        if ($session->get('filtroNumeroGuia')) {
            $queryBuilder->andWhere("tg.codigoGuiaPk = '{$session->get('filtroNumeroGuia')}'");
        }
        if ($session->get('filtroConductor') != '') {
            $queryBuilder->andWhere("ct.nombreCorto LIKE '%{$session->get('filtroConductor')}%' ");
        }
        if ($session->get('filtroDocumentoCliente')) {
            $queryBuilder->andWhere("tg.documentoCliente = '{$session->get('filtroDocumentoCliente')}'");
        }
        $queryBuilder->orderBy('tg.codigoGuiaPk', 'DESC');

        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendienteRecaudoDevolucion()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.vrManejo')
            ->addSelect('tg.vrRecaudo')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.despachoRel', 'dg')
            ->where('tg.estadoRecaudoDevolucion = 0')
            ->andWhere('tg.vrRecaudo > 0');
        $queryBuilder->orderBy('tg.codigoGuiaPk', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("tg.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("tg.fechaIngreso >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("tg.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("tg.fechaIngreso <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("tg.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendienteRecaudoCobro()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.vrManejo')
            ->addSelect('tg.vrRecaudo')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.despachoRel', 'dg')
            ->where('tg.estadoRecaudoCobro = 0')
            ->andWhere('tg.vrRecaudo > 0');
        $queryBuilder->orderBy('tg.codigoGuiaPk', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("tg.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("tg.fechaIngreso >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("tg.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("tg.fechaIngreso <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("tg.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function guiasCliente()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk AS guia')
            ->addSelect('c.nombreCorto AS cliente')
            ->addSelect('tg.documentoCliente AS docCliente')
            ->addSelect('tg.nombreDestinatario ')
            ->addSelect('tg.direccionDestinatario')
            ->addSelect('cd.nombre AS ciudad')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoFacturado AS peso')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.vrManejo')
            ->addSelect('tg.vrFlete + tg.vrManejo AS vrTotal')
            ->addSelect('tg.vrDeclara')
            ->addSelect('tg.fechaIngreso')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->where('tg.codigoGuiaPk <> 0');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }

        if ($session->get('filtroTteFechaDesde') != null) {
            $queryBuilder->andWhere("tg.fechaIngreso >= '{$session->get('filtroTteFechaDesde')} 00:00:00'");
        } else {
            $queryBuilder->andWhere("tg.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }
        if ($session->get('filtroTteFechaHasta') != null) {
            $queryBuilder->andWhere("tg.fechaIngreso <= '{$session->get('filtroTteFechaHasta')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("tg.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
        }
        $queryBuilder->orderBy('tg.fechaIngreso', 'DESC');

        return $queryBuilder;
    }

    /**
     * @param $codigoCumplido
     * @return mixed
     */
    public function cumplido($codigoCumplido)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.codigoServicioFk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrRecaudo')
            ->addSelect('g.empaqueReferencia')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoCumplidoFk =' . $codigoCumplido);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoRecaudo
     * @return mixed
     */
    public function recaudo($codigoRecaudo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrRecaudo')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoRecaudoDevolucionFk =' . $codigoRecaudo);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoRecaudo
     * @return mixed
     */
    public function recaudoCobro($codigoRecaudoCobro)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrRecaudo')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoRecaudoCobroFk =' . $codigoRecaudoCobro);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoFactura
     * @return mixed
     */
    public function factura($codigoFactura)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumenl')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoFacturaFk =' . $codigoFactura);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoCliente
     * @return mixed
     */
    public function facturaPendiente($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.codigoServicioFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.estadoFacturaGenerada = 0')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.factura = 0')
            ->andWhere('g.cortesia = 0')
            ->andWhere('g.codigoClienteFk =' . $codigoCliente);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return mixed
     */
    public function pendienteGenerarFactura()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.estadoImpreso = 1')
            ->andWhere('g.estadoFacturaGenerada = 0')
            ->andWhere('g.factura = 1')
            ->orderBy('g.codigoRutaFk')
            ->addOrderBy('g.codigoCiudadDestinoFk');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrGuias
     * @param $arrControles
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function entrega($arrGuias, $arrControles): bool
    {
        $em = $this->getEntityManager();
        if ($arrGuias) {
            if ($arrGuias) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    if ($arGuia->getEstadoDespachado() == 1 && $arGuia->getEstadoEntregado() == 0) {
                        $fechaHora = date_create($arrControles['txtFechaEntrega' . $codigoGuia] . " " . $arrControles['txtHoraEntrega' . $codigoGuia]);
                        $arGuia->setFechaEntrega($fechaHora);
                        $arGuia->setEstadoEntregado(1);
                        if (isset($arrControles['chkSoporte']) && $arrControles['chkSoporte']) {
                            if (!$arGuia->getEstadoSoporte()) {
                                $arGuia->setEstadoSoporte(1);
                                $arGuia->setFechaSoporte(new  \DateTime('now'));
                            }
                        }
                        $em->persist($arGuia);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $arrGuias
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function soporte($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if ($arrGuias) {
            foreach ($arrGuias AS $codigoGuia) {
                $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                if ($arGuia) {
                    if ($arGuia->getEstadoDespachado() && $arGuia->getEstadoEntregado() && !$arGuia->getEstadoSoporte()) {
                        $arGuia->setFechaSoporte(new \DateTime("now"));
                        $arGuia->setEstadoSoporte(1);
                        $em->persist($arGuia);
                    }
                }
            }
            $em->flush();
        }
        return true;
    }

    /**
     * @param $arrGuias
     * @param $usuario
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generarFactura($arrGuias, $usuario): bool
    {
        $em = $this->getEntityManager();
        if ($arrGuias) {
            if ($arrGuias) {
                //Verificar tercero, esto para evitar que se dupliquen los tercero ya que cuando no existen
                //se crean varias veces porque no se hace flush y tiene varias facturas del mismo cliente
                //Mario Estrada

                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->guiaCliente($codigoGuia);
                    if ($arGuia) {
                        $arClienteCartera = $em->getRepository(CarCliente::class)->findOneBy(['codigoIdentificacionFk' => $arGuia['codigoIdentificacionFk'], 'numeroIdentificacion' => $arGuia['numeroIdentificacion']]);
                        if (!$arClienteCartera) {
                            $arClienteCartera = new CarCliente();
                            if ($arGuia['codigoFormaPagoFk']) {
                                $arFormaPago = $em->getRepository(GenFormaPago::class)->find($arGuia['codigoFormaPagoFk']);
                                $arClienteCartera->setFormaPagoRel($arFormaPago);
                            }
                            $arIdentificacion = $em->getRepository(GenIdentificacion::class)->find($arGuia['codigoIdentificacionFk']);
                            $arClienteCartera->setIdentificacionRel($arIdentificacion);
                            $arClienteCartera->setNumeroIdentificacion($arGuia['numeroIdentificacion']);
                            $arClienteCartera->setDigitoVerificacion($arGuia['digitoVerificacion']);
                            $arClienteCartera->setNombreCorto($arGuia['nombreCorto']);
                            $arClienteCartera->setPlazoPago($arGuia['plazoPago']);
                            $arClienteCartera->setDireccion($arGuia['direccion']);
                            $arClienteCartera->setTelefono($arGuia['telefono']);
                            $arClienteCartera->setCorreo($arGuia['correo']);
                            $em->persist($arClienteCartera);
                            $em->flush();
                        }
                    }
                }

                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    if (!$arGuia->getEstadoFacturaExportado()) {
                        $fechaActual = new \DateTime('now');
                        $arGuia->setEstadoFacturaExportado(1);
                        $arGuia->setEstadoFacturaGenerada(1);
                        $arGuia->setEstadoFacturado(1);
                        $arGuia->setFechaFactura($fechaActual);
                        $em->persist($arGuia);

                        $arFactura = new TteFactura();
                        $arFactura->setFacturaTipoRel($arGuia->getGuiaTipoRel()->getFacturaTipoRel());
                        $arFactura->setOperacionComercial($arFactura->getFacturaTipoRel()->getOperacionComercial());
                        $arFactura->setCodigoFacturaClaseFk('FA');
                        $arFactura->setOperacionRel($arGuia->getOperacionIngresoRel());
                        $arFactura->setNumero($arGuia->getNumeroFactura());
                        $arFactura->setFecha($arGuia->getFechaIngreso());
                        $arFactura->setFechaVence($arGuia->getFechaIngreso());
                        $total = $arGuia->getVrFlete() + $arGuia->getVrManejo();
                        $arFactura->setVrFlete($arGuia->getVrFlete());
                        $arFactura->setVrManejo($arGuia->getVrManejo());
                        $arFactura->setVrSubtotal($total);
                        $arFactura->setVrTotal($total);
                        $arFactura->setVrTotalOperado($total * $arFactura->getFacturaTipoRel()->getOperacionComercial());
                        $arFactura->setGuias(1);
                        $arFactura->setClienteRel($arGuia->getClienteRel());
                        $arFactura->setEstadoAutorizado(1);
                        $arFactura->setEstadoAprobado(1);
                        $arFactura->setEstadoAnulado($arGuia->getEstadoAnulado());
                        $arFactura->setUsuario($usuario);
                        $em->persist($arFactura);

                        $arFacturaDetalle = new TteFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setGuiaRel($arGuia);
                        $arFacturaDetalle->setVrManejo($arGuia->getVrManejo());
                        $arFacturaDetalle->setVrFlete($arGuia->getVrFlete());
                        $arFacturaDetalle->setPesoReal($arGuia->getPesoReal());
                        $arFacturaDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                        $arFacturaDetalle->setUnidades($arGuia->getUnidades());
                        $arFacturaDetalle->setVrDeclara($arGuia->getVrDeclara());
                        $em->persist($arFacturaDetalle);

                        $arCuentaCobrarTipo = $em->getRepository(CarCuentaCobrarTipo::class)->find($arFactura->getFacturaTipoRel()->getCodigoCuentaCobrarTipoFk());
                        $arCuentaCobrar = new CarCuentaCobrar();
                        $arClienteCartera = $em->getRepository(CarCliente::class)->findOneBy(['codigoIdentificacionFk' => $arGuia->getClienteRel()->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arGuia->getClienteRel()->getNumeroIdentificacion()]);
                        $arCuentaCobrar->setClienteRel($arClienteCartera);
                        $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                        $arCuentaCobrar->setFecha($arFactura->getFecha());
                        $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                        $arCuentaCobrar->setModulo("TTE");
                        $arCuentaCobrar->setNumeroReferencia($arGuia->getCodigoDespachoFk());
                        $arCuentaCobrar->setCodigoDocumento($arFactura->getCodigoFacturaPk());
                        $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                        $arCuentaCobrar->setSoporte($arGuia->getNumero());
                        $arCuentaCobrar->setVrSubtotal($arFactura->getVrSubtotal());
                        $arCuentaCobrar->setVrTotal($arFactura->getVrTotal());
                        $arCuentaCobrar->setVrSaldo($arFactura->getVrTotal());
                        $arCuentaCobrar->setVrSaldoOriginal($arFactura->getVrTotal());
                        $arCuentaCobrar->setVrSaldoOperado($arFactura->getVrTotal() * $arCuentaCobrarTipo->getOperacion());
                        $arCuentaCobrar->setPlazo($arFactura->getPlazoPago());
                        $arCuentaCobrar->setOperacion($arCuentaCobrarTipo->getOperacion());
                        $arCuentaCobrar->setEstadoAnulado($arGuia->getEstadoAnulado());
                        $em->persist($arCuentaCobrar);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $codigoCliente
     * @return array
     */
    public function cumplidoPendiente($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.estadoCumplido = 0')
            ->andWhere('g.estadoSoporte = 1')
            ->andWhere('g.codigoClienteFk =' . $codigoCliente)
            ->andWhere("g.fechaIngreso >= '2018-04-01'")
            ->orderBy('g.fechaIngreso', 'DESC');

        return $queryBuilder->getQuery()->getResult();

    }

    /**
     * @param $codigoCliente
     * @return array
     */
    public function recaudoPendiente($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrRecaudo')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.estadoRecaudoDevolucion = 0')
            ->andWhere('g.vrRecaudo > 0')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.codigoClienteFk =' . $codigoCliente)
            ->orderBy('g.fechaIngreso', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoCliente
     * @return array
     */
    public function recaudoCobroPendiente()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrRecaudo')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoRecaudoCobroFk is null')
            ->andwhere('g.estadoRecaudoCobro = 0')
            ->andWhere('g.vrRecaudo > 0')
            ->andWhere('g.estadoAnulado = 0')
            ->orderBy('g.fechaIngreso', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return mixed
     */
    public function informeDespachoPendienteRuta()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.codigoServicioFk')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.codigoRutaFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('r.nombre AS nombreRuta')
            ->addSelect('g.estadoNovedad')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.rutaRel', 'r')
            ->where('g.estadoEmbarcado = 0')
            ->andWhere('g.estadoAnulado = 0');
        if ($session->get('filtroTteCodigoRuta')) {
            $queryBuilder->andWhere("g.codigoRutaFk = '{$session->get('filtroTteCodigoRuta')}'");
        }
        if ($session->get('filtroTteCodigoServicio')) {
            $queryBuilder->andWhere("g.codigoServicioFk = '{$session->get('filtroTteCodigoServicio')}'");
        }
        if ($session->get('filtroTteCodigoOperacionCargo')) {
            $queryBuilder->andWhere("g.codigoOperacionCargoFk = '{$session->get('filtroTteCodigoOperacionCargo')}'");
        }
        if (!$session->get('filtroTteMostrarDevoluciones')) {
            $queryBuilder->andWhere("g.codigoServicioFk <> 'DEV'");
        }
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("g.codigoClienteFk = '{$session->get('filtroTteCodigoCliente')}'");
        }
        switch ($session->get('filtroTteNovedadGuia')) {
            case '0':
                $queryBuilder->andWhere("g.estadoNovedad = 0");
                break;
            case '1':
                $queryBuilder->andWhere("g.estadoNovedad = 1");
                break;
        }

        $queryBuilder->orderBy('g.codigoRutaFk')
            ->addOrderBy('g.codigoCiudadDestinoFk');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $fechaDesde
     * @param $fechaHasta
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function informeProduccionCliente($fechaDesde, $fechaHasta)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoClienteFk')
            ->addSelect('g.mercanciaPeligrosa')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('SUM(g.vrFlete) AS vrFlete')
            ->addSelect('SUM(g.vrManejo) AS vrManejo')
            ->addSelect('SUM(g.unidades) AS unidades')
            ->addSelect('SUM(g.pesoReal) AS pesoReal')
            ->addSelect('SUM(g.vrFlete + g.vrManejo) AS total')
            ->leftJoin('g.clienteRel', 'c')
            ->where("g.fechaIngreso >= '" . $fechaDesde . " 00:00:00'")
            ->andWhere("g.fechaIngreso <= '" . $fechaHasta . " 23:59:59'")
            ->groupBy('g.codigoClienteFk')
            ->addGroupBy('c.nombreCorto')
            ->addGroupBy('g.mercanciaPeligrosa')
            ->orderBy('SUM(g.vrFlete)', 'DESC');
        if ($session->get('filtroMercanciaPeligrosa')) {
            $queryBuilder->andWhere("g.mercanciaPeligrosa = 1");
        }
        if ($session->get('filtroTteGuiaCodigoGuiaTipo')) {
            $queryBuilder->andWhere("g.codigoGuiaTipoFk = '" . $session->get('filtroTteGuiaCodigoGuiaTipo') . "'");
        }
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("g.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        return $queryBuilder;
    }


    public function informeProduccionAsesor($fechaDesde, $fechaHasta)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->from("App:Transporte\TteGuia", 'g')
            ->select('c.codigoAsesorFk')
            ->addSelect('a.nombre AS asesorNombre')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('SUM(g.vrFlete) AS vrFlete')
            ->addSelect('SUM(g.vrManejo) AS vrManejo')
            ->addSelect('SUM(g.unidades) AS unidades')
            ->addSelect('SUM(g.pesoReal) AS pesoReal')
            ->addSelect('SUM(g.vrFlete + g.vrManejo) AS total')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('c.asesorRel', 'a')
            ->where("g.fechaIngreso >= '" . $fechaDesde . " 00:00:00'")
            ->andWhere("g.fechaIngreso <= '" . $fechaHasta . " 23:59:59'")
            ->groupBy('c.codigoAsesorFk')
            ->addGroupBy('c.nombreCorto')
            ->orderBy('c.codigoAsesorFk')
            ->addOrderBy('SUM(g.vrFlete)', 'DESC');
        if ($session->get('filtroTteGuiaCodigoGuiaTipo')) {
            $queryBuilder->andWhere("g.codigoGuiaTipoFk = '" . $session->get('filtroTteGuiaCodigoGuiaTipo') . "'");
        }
        if ($session->get('filtroTteGuiaCodigoAsesor')) {
            $queryBuilder->andWhere("c.codigoAsesorFk = '" . $session->get('filtroTteGuiaCodigoAsesor') . "'");
        }
        return $queryBuilder;
    }

    /**
     * @param $fechaDesde
     * @param $fechaHasta
     * @return mixed
     */
    public function utilidadNotificarEntrega($fechaDesde, $fechaHasta)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoClienteFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('c.correo')
            ->addSelect('COUNT(g.codigoGuiaPk) AS numero')
            ->leftJoin('g.clienteRel', 'c')
            ->where("g.fechaSoporte >= '" . $fechaDesde . " 00:00:00'")
            ->andWhere("g.fechaSoporte <= '" . $fechaHasta . " 23:59:59'")
            ->andWhere('g.estadoEntregado = 1')
            ->groupBy('g.codigoClienteFk')
            ->orderBy('COUNT(g.codigoGuiaPk)', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoCliente
     * @param $fechaDesde
     * @param $fechaHasta
     * @return mixed
     */
    public function utilidadNotificarEntregaDetalle($codigoCliente, $fechaDesde, $fechaHasta)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.codigoServicioFk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.unidades')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.fechaEntrega')
            ->addSelect('g.estadoNovedad')
            ->addSelect('g.estadoNovedadSolucion')
            ->addSelect('cd.nombre as destino')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.clienteRel', 'c')
            ->where("g.fechaSoporte >= '" . $fechaDesde . " 00:00:00'")
            ->andWhere("g.fechaSoporte <= '" . $fechaHasta . " 23:59:59'")
            ->andWhere('g.estadoEntregado = 1')
            ->andWhere('g.codigoClienteFk = ' . $codigoCliente);
        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendienteSoporte()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.numero')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('d.numero AS despacho')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.despachoRel', 'd')
            ->where('g.estadoDespachado = 1')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.estadoSoporte = 0')
            ->andWhere('g.codigoDespachoFk <> 0')
            ->orderBy('g.fechaIngreso', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTtePendienteSoporteFechaDesde') != null) {
            $queryBuilder->andWhere("g.fechaIngreso >= '{$session->get('filtroTtePendienteSoporteFechaDesde')} 00:00:00'");
        } else {
            $queryBuilder->andWhere("g.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }
        if ($session->get('filtroTtePendienteSoporteFechaHasta') != null) {
            $queryBuilder->andWhere("g.fechaIngreso <= '{$session->get('filtroTtePendienteSoporteFechaHasta')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("g.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
        }
        if ($session->get('filtroNumeroDespacho')) {
            $queryBuilder->andWhere("d.numero = '{$session->get('filtroNumeroDespacho')}'");
        }

        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendienteConductor()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.numero')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('d.codigoConductorFk')
            ->addSelect('con.nombreCorto AS conductor')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.estadoNovedad')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.despachoRel', 'd')
            ->leftJoin('d.conductorRel', 'con')
            ->where('g.estadoDespachado = 1')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.estadoSoporte = 0')
            ->andWhere('g.codigoDespachoFk <> 0')
            ->groupBy('d.codigoConductorFk')
            ->addGroupBy('g.codigoGuiaPk');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoConductor')) {
            $queryBuilder->andWhere("d.codigoConductorFk = '" . $session->get('filtroTteCodigoConductor') . "'");
        }
        if ($session->get('filtroTtePendienteSoporteFechaDesde') != null) {
            $queryBuilder->andWhere("g.fechaIngreso >= '{$session->get('filtroTtePendienteSoporteFechaDesde')} 00:00:00'");
        } else {
            $queryBuilder->andWhere("g.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }
        if ($session->get('filtroTtePendienteSoporteFechaHasta') != null) {
            $queryBuilder->andWhere("g.fechaIngreso <= '{$session->get('filtroTtePendienteSoporteFechaHasta')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("g.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
        }
        switch ($session->get('filtroTteNovedadGuia')) {
            case '0':
                $queryBuilder->andWhere("g.estadoNovedad = 0");
                break;
            case '1':
                $queryBuilder->andWhere("g.estadoNovedad = 1");
                break;
        }

        return $queryBuilder;
    }

    /**
     * @return mixed
     */
    public function pendiente()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.codigoRutaFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('con.nombreCorto AS nombreConductor')
            ->addSelect('d.codigoConductorFk')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.despachoRel', 'd')
            ->leftJoin('g.conductorRel', 'con')
            ->where('g.estadoDespachado = 1')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.estadoSoporte = 0')
            ->andWhere('g.codigoDespachoFk != null');
        $queryBuilder->orderBy('d.codigoCOnductorFk')
            ->addOrderBy('g.codigoCiudadDestinoFk');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function pendientesDespachoCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.estadoDespachado = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function pendientesEntregaCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.estadoDespachado = 1 AND g.estadoEntregado = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function pendientesCumplidoCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.estadoDespachado = 1 AND g.estadoEntregado = 1 AND g.estadoCumplido = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function resumenPendientesCuenta(): array
    {
        $arrResumen = array();
        $despacho = $this->pendientesDespachoCuenta();
        $entregar = $this->pendientesEntregaCuenta();
        $cumplir = $this->pendientesCumplidoCuenta();
        $arrResumen['despachar'] = $despacho;
        $arrResumen['entregar'] = $entregar;
        $arrResumen['cumplir'] = $cumplir;
        return $arrResumen;
    }

    public function imprimir($codigoGuia): bool
    {
        $em = $this->getEntityManager();
        $respuesta = false;
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia->getEstadoImpreso() == 0) {
            if ($arGuia->getNumero() == 0 || $arGuia->getNumero() == NULL) {
                $arGuiaTipo = $em->getRepository(TteGuiaTipo::class)->find($arGuia->getCodigoGuiaTipoFk());
                $arGuia->setNumero($arGuiaTipo->getConsecutivo());
                $arGuiaTipo->setConsecutivo($arGuiaTipo->getConsecutivo() + 1);
                $em->persist($arGuiaTipo);
            }
            $arGuia->setEstadoImpreso(1);
            $em->persist($arGuia);
            $respuesta = true;
        }
        return $respuesta;
    }

    public function periodoCierre($anio, $mes): array
    {
        $em = $this->getEntityManager();
        $ultimoDia = date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
        $fechaDesde = $anio . "-" . $mes . "-" . "01 00:00";
        $fechaHasta = $anio . "-" . $mes . "-" . $ultimoDia . " 23:59";
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk,  
        g.vrFlete
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.fechaIngreso >= :fechaDesde AND g.fechaIngreso <= :fechaHasta  
        ORDER BY g.codigoGuiaPk DESC '
        )->setParameter('fechaDesde', $fechaDesde)
            ->setParameter('fechaHasta', $fechaHasta);
        return $query->execute();
    }

    public function procesoCosto($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.vrFlete        
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.codigoGuiaPk >= :guia'
        )->setParameter('guia', $codigoGuia);
        return $query->execute();
    }

    /**
     * @param $codigoGuia
     * @param $redespachoMotivo
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function redespacho($codigoGuia, $redespachoMotivo): bool
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia) {
            if ($arGuia->getEstadoDespachado() == 1 && $arGuia->getCodigoDespachoFk() && $arGuia->getEstadoAnulado() == 0) {
                $arRedespacho = New TteRedespacho();
                $arGuia->setFechaDespacho(NULL);
                $arGuia->setFechaCumplido(NULL);
                $arGuia->setFechaEntrega(NULL);
                $arGuia->setEstadoDespachado(0);
                $arGuia->setEstadoEmbarcado(0);
                $arGuia->setEstadoEntregado(0);
                $arGuia->setEstadoSoporte(0);
                $arRedespacho->setRedespachoDespachoRel($arGuia->getDespachoRel());
                $arGuia->setCodigoDespachoFk(NULL);
                $em->persist($arGuia);
                //Se crea el redespacho;
                $arRedespacho->setFecha(new \DateTime('now'));
                $arRedespacho->setRedespachoGuiaRel($arGuia);
                $arRedespacho->setRedespachoMotivoRel($redespachoMotivo);
                $em->persist($arRedespacho);
                $em->flush();
                Mensajes::success("La guia se activo para redespacho");
            } else {
                Mensajes::error("La guia debe estar despachada y no puede estar anulada");
            }
        } else {
            Mensajes::error("No existe la guia");
        }
        return true;
    }

    public function cortesia($codigoGuia): bool
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia) {
            if ($arGuia->getCortesia() == 0 && $arGuia->getFactura() == 0 && $arGuia->getEstadoAnulado() == 0) {
                if ($arGuia->getCodigoFacturaFk() == null) {
                    $arGuia->setVrFlete(0);
                    $arGuia->setVrManejo(0);
                    $arGuia->setCortesia(1);
                    $em->persist($arGuia);
                    $em->flush();
                    Mensajes::success("La guia se genero como una cortesia");
                } else {
                    Mensajes::error("La guia no puede estar en una factura");
                }
            } else {
                Mensajes::error("La guia no puede ser una cortesia ni una factura ni estar anulada");
            }
        } else {
            Mensajes::error("No existe la guia");
        }
        return true;
    }

    /**
     * @param $codigoGuia
     * @param $documentoCliente
     * @return array
     */
    public function apiConsulta($codigoGuia, $documentoCliente)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoGuiaPk as codigoGuia')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.estadoEmbarcado as embarcado')
            ->addSelect('g.estadoDespachado as despachado')
            ->addSelect('g.fechaDespacho as fechaDespachado')
            ->addSelect('g.estadoEntregado as entregado')
            ->addSelect('g.fechaEntrega as fechaEntregado')
            ->addSelect('g.fechaDesembarco as fechaDesembarco')
            ->addSelect('g.estadoSoporte as soporte')
            ->addSelect('g.fechaSoporte as fechaSoporte')
            ->addSelect('g.estadoCumplido as cumplido')
            ->addSelect('g.fechaCumplido as fechaCumplido')
            ->addSelect('g.estadoNovedad as novedad')
            ->addSelect('g.estadoNovedadSolucion')
            ->addSelect('g.unidades')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.remitente')
            ->addSelect('co.nombre as ciudadOrigen')
            ->addSelect('cd.nombre as ciudadDestino')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.telefonoDestinatario')
            ->addSelect('g.direccionDestinatario')
            ->addSelect('gt.nombre as tipoGuia')
            ->leftJoin('g.ciudadOrigenRel', 'co')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.guiaTipoRel', 'gt');
        if ($codigoGuia != 0) {
            $queryBuilder->where("g.codigoGuiaPk = " . $codigoGuia);
        } else {
            $queryBuilder->where("g.documentoCliente = " . $documentoCliente);
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiDespacho($codigoDespacho)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoGuiaPk')
            ->addSelect('g.nombreDestinatario AS destinatario')
            ->addSelect('cd.nombre AS destino')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where("g.codigoDespachoFk = " . $codigoDespacho)
            ->andWhere('g.estadoEntregado = 0')
            ->orderBy('g.ordenRuta');
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiEntrega($codigoGuia, $fecha, $hora, $soporte)
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia) {
            if ($arGuia->getEstadoDespachado() == 1) {
                if ($arGuia->getEstadoEntregado() == 0) {
                    $fechaHora = date_create($fecha . " " . $hora);
                    $arGuia->setFechaEntrega($fechaHora);
                    $arGuia->setEstadoEntregado(1);
                    if ($soporte == "si") {
                        $arGuia->setEstadoSoporte(1);
                        $arGuia->setFechaSoporte(new \DateTime('now'));
                    }
                    $em->persist($arGuia);
                    $em->flush();
                    return [
                        'error' => false,
                        'mensaje' => '',
                    ];
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede estar entregada previamente',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia no esta despachada',
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " no existe " . $fecha . " " . $hora,
            ];
        }
    }

    /**
     * @param $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiDesembarco($codigoGuia, $arOperacion)
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia) {
            if ($arGuia->getEstadoDespachado() == 1) {
                if ($arGuia->getEstadoEntregado() == 0) {
                    $arDesembarco = new TteDesembarco();
                    $arGuia->setFechaDespacho(null);
                    $arGuia->setFechaCumplido(null);
                    $arGuia->setFechaEntrega(null);
                    $arGuia->setFechaSoporte(null);
                    $arGuia->setEstadoDespachado(0);
                    $arGuia->setEstadoEmbarcado(0);
                    $arGuia->setEstadoEntregado(0);
                    $arGuia->setEstadoSoporte(0);
                    $arGuia->setOperacionCargoRel($arOperacion);
                    $arDesembarco->setDespachoRel($arGuia->getDespachoRel());
                    $arDesembarco->setGuiaRel($arGuia);
                    $arDesembarco->setFecha(new \DateTime('now'));
                    $arGuia->setCodigoDespachoFk(null);
                    $em->persist($arGuia);
                    $em->persist($arDesembarco);
                    $em->flush();
                    return [
                        'error' => false,
                        'mensaje' => '',
                    ];
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede estar entregada previamente',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia no esta despachada',
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " no existe",
            ];
        }
    }

    /**
     * @param $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiSoporte($codigoGuia)
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia) {
            if ($arGuia->getEstadoEntregado() == 1) {
                $arGuia->setEstadoSoporte(1);
                $arGuia->setFechaSoporte(new \DateTime('now'));
                $em->persist($arGuia);
                $em->flush();
                return [
                    'error' => false,
                    'mensaje' => '',
                ];
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia debe estar entregada',
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " no existe ",
            ];
        }
    }

    /**
     * @param $codigoDespacho $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiDespachoAdicionar($codigoDespacho, $codigoGuia)
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        if ($arGuia && $arDespacho) {
            if ($arGuia->getEstadoEmbarcado() == 0 && $arGuia->getCodigoDespachoFk() == null) {
                if ($arDespacho->getCodigoOperacionFk() == $arGuia->getCodigoOperacionCargoFk()) {
                    $arGuia->setDespachoRel($arDespacho);
                    $arGuia->setEstadoEmbarcado(1);
                    $em->persist($arGuia);

                    $arDespachoDetalle = new TteDespachoDetalle();
                    $arDespachoDetalle->setDespachoRel($arDespacho);
                    $arDespachoDetalle->setGuiaRel($arGuia);
                    $arDespachoDetalle->setVrDeclara($arGuia->getVrDeclara());
                    $arDespachoDetalle->setVrFlete($arGuia->getVrFlete());
                    $arDespachoDetalle->setVrManejo($arGuia->getVrManejo());
                    $arDespachoDetalle->setVrRecaudo($arGuia->getVrRecaudo());
                    $arDespachoDetalle->setVrCobroEntrega($arGuia->getVrCobroEntrega());
                    $arDespachoDetalle->setUnidades($arGuia->getUnidades());
                    $arDespachoDetalle->setPesoReal($arGuia->getPesoReal());
                    $arDespachoDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                    if ($arGuia->getPesoReal() >= $arGuia->getPesoVolumen()) {
                        $arDespachoDetalle->setPesoCosto($arGuia->getPesoReal());
                    } else {
                        $arDespachoDetalle->setPesoCosto($arGuia->getPesoVolumen());
                    }
                    $em->persist($arDespachoDetalle);

                    $arDespacho->setUnidades($arDespacho->getUnidades() + $arGuia->getUnidades());
                    $arDespacho->setPesoReal($arDespacho->getPesoReal() + $arGuia->getPesoReal());
                    $arDespacho->setPesoVolumen($arDespacho->getPesoVolumen() + $arGuia->getPesoVolumen());
                    $arDespacho->setPesoCosto($arDespacho->getPesoCosto() + $arDespachoDetalle->getPesoCosto());
                    $arDespacho->setCantidad($arDespacho->getCantidad() + 1);
                    $em->persist($arDespacho);
                    $em->flush();
                    return [
                        'error' => false,
                        'mensaje' => '',
                    ];
                } else {
                    return [
                        'error' => true,
                        'mensaje' => "La guia esta a cargo de la operacion " . $arGuia->getCodigoOperacionCargoFk() . " no puede ser despachada con la operacion " . $arDespacho->getCodigoOperacionFk(),
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia ya esta embarcada en el despacho ' . $arGuia->getCodigoDespachoFk(),
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " o el despacho " . $codigoDespacho . " no existe ",
            ];
        }
    }

    /**
     * @param $codigoDespacho $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiDespachoAdicionarDocumento($codigoDespacho, $documento)
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->findOneBy(array('documentoCliente' => $documento, 'estadoEmbarcado' => 0, 'estadoAnulado' => 0, 'codigoDespachoFk' => null));
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        if ($arDespacho) {
            if ($arGuia) {
                if ($arDespacho->getCodigoOperacionFk() == $arGuia->getCodigoOperacionCargoFk()) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($arGuia->getCodigoGuiaPk());
                    $arGuia->setDespachoRel($arDespacho);
                    $arGuia->setEstadoEmbarcado(1);
                    $em->persist($arGuia);

                    $arDespachoDetalle = new TteDespachoDetalle();
                    $arDespachoDetalle->setDespachoRel($arDespacho);
                    $arDespachoDetalle->setGuiaRel($arGuia);
                    $arDespachoDetalle->setVrDeclara($arGuia->getVrDeclara());
                    $arDespachoDetalle->setVrFlete($arGuia->getVrFlete());
                    $arDespachoDetalle->setVrManejo($arGuia->getVrManejo());
                    $arDespachoDetalle->setVrRecaudo($arGuia->getVrRecaudo());
                    $arDespachoDetalle->setVrCobroEntrega($arGuia->getVrCobroEntrega());
                    $arDespachoDetalle->setUnidades($arGuia->getUnidades());
                    $arDespachoDetalle->setPesoReal($arGuia->getPesoReal());
                    $arDespachoDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                    if ($arGuia->getPesoReal() >= $arGuia->getPesoVolumen()) {
                        $arDespachoDetalle->setPesoCosto($arGuia->getPesoReal());
                    } else {
                        $arDespachoDetalle->setPesoCosto($arGuia->getPesoVolumen());
                    }
                    $em->persist($arDespachoDetalle);

                    $arDespacho->setUnidades($arDespacho->getUnidades() + $arGuia->getUnidades());
                    $arDespacho->setPesoReal($arDespacho->getPesoReal() + $arGuia->getPesoReal());
                    $arDespacho->setPesoVolumen($arDespacho->getPesoVolumen() + $arGuia->getPesoVolumen());
                    $arDespacho->setPesoCosto($arDespacho->getPesoCosto() + $arDespachoDetalle->getPesoCosto());
                    $arDespacho->setCantidad($arDespacho->getCantidad() + 1);
                    $em->persist($arDespacho);
                    $em->flush();
                    return [
                        'error' => false,
                        'mensaje' => '',
                    ];
                } else {
                    return [
                        'error' => true,
                        'mensaje' => "La guia esta a cargo de la operacion " . $arGuia->getCodigoOperacionCargoFk() . " no puede ser despachada con la operacion " . $arDespacho->getCodigoOperacionFk(),
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => "La guia con documento " . $documento . " no existe o ya esta embarcada en otro despacho",
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "El despacho " . $codigoDespacho . " no existe ",
            ];
        }
    }

    /**
     * @param $codigoDespacho $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiFacturaAdicionar($codigoFactura, $codigoGuia, $documento, $tipo)
    {
        $em = $this->getEntityManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arGuia = NULL;

        if ($tipo == 1) {
            $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        }
        if ($tipo == 2) {
            $arGuiaDocumento = $em->getRepository(TteGuia::class)->findOneBy(array(
                'documentoCliente' => $documento,
                'codigoClienteFk' => $arFactura->getCodigoClienteFk(),
                'estadoFacturaGenerada' => 0,
                'codigoFacturaFk' => null,
                'estadoFacturado' => 0,
                'estadoFacturaExportado' => 0,
                'estadoFacturado' => 0));
            if ($arGuiaDocumento) {
                $arGuia = $em->getRepository(TteGuia::class)->find($arGuiaDocumento->getCodigoGuiaPk());
            } else {
                $arGuia = "";
            }
        }

        if ($arGuia && $arFactura) {
            if ($arGuia->getEstadoFacturaGenerada() == 0 && $arGuia->getEstadoFacturado() == 0 && !$arGuia->getCodigoFacturaFk()) {
                if ($arGuia->getFactura() == 0 && $arGuia->getEstadoAnulado() == 0) {
                    if ($arGuia->getCodigoClienteFk() == $arFactura->getCodigoClienteFk()) {
                        $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->retencionTransporte();
                        $arGuia->setFacturaRel($arFactura);
                        $arGuia->setEstadoFacturaGenerada(1);
                        $em->persist($arGuia);

                        $arFacturaDetalle = new TteFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setGuiaRel($arGuia);
                        $arFacturaDetalle->setVrDeclara($arGuia->getVrDeclara());
                        $arFacturaDetalle->setVrFlete($arGuia->getVrFlete());
                        $arFacturaDetalle->setVrManejo($arGuia->getVrManejo());
                        $arFacturaDetalle->setUnidades($arGuia->getUnidades());
                        $arFacturaDetalle->setPesoReal($arGuia->getPesoReal());
                        $arFacturaDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                        $arFacturaDetalle->setCodigoImpuestoRetencionFk($arrConfiguracion['codigoImpuestoRetencionTransporteFk']);
                        $em->persist($arFacturaDetalle);

                        $arFactura->setGuias($arFactura->getGuias() + 1);
                        $arFactura->setVrFlete($arFactura->getVrFlete() + $arGuia->getVrFlete());
                        $arFactura->setVrManejo($arFactura->getVrManejo() + $arGuia->getVrManejo());
                        $subtotal = $arFactura->getVrSubtotal() + $arGuia->getVrFlete() + $arGuia->getVrManejo();
                        $arFactura->setVrSubtotal($subtotal);
                        $arFactura->setVrTotal($subtotal);
                        $em->persist($arFactura);
                        $em->flush();
                        return [
                            'error' => false,
                            'mensaje' => '',
                        ];
                    } else {
                        return [
                            'error' => true,
                            'mensaje' => 'La guia es de otro cliente y no se puede adicionar a la factura ',
                        ];
                    }
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede ser una factura de venta y no puede estar anulada',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia ya esta prefacturada o facturada en la factura ' . $arGuia->getCodigoFacturaFk(),
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " o la factura " . $codigoFactura . " no existe ",
            ];
        }
    }

    /**
     * @param $codigoDespacho $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiFacturaPlanillaAdicionar($codigoFacturaPlanilla, $codigoGuia, $documento, $tipo)
    {
        $em = $this->getEntityManager();
        $arFacturaPlanilla = $em->getRepository(TteFacturaPlanilla::class)->find($codigoFacturaPlanilla);
        $arFactura = $em->getRepository(TteFactura::class)->find($arFacturaPlanilla->getCodigoFacturaFk());
        $arGuia = NULL;

        if ($tipo == 1) {
            $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        }
        if ($tipo == 2) {
            $arGuiaDocumento = $em->getRepository(TteGuia::class)->findOneBy(array(
                'documentoCliente' => $documento,
                'codigoClienteFk' => $arFactura->getCodigoClienteFk(),
                'estadoFacturaGenerada' => 0));
            if ($arGuiaDocumento) {
                $arGuia = $em->getRepository(TteGuia::class)->find($arGuiaDocumento->getCodigoGuiaPk());
            } else {
                $arGuia = "";
            }
        }

        if ($arGuia && $arFactura) {
            if ($arGuia->getEstadoFacturaGenerada() == 0) {
                if ($arGuia->getFactura() == 0 && $arGuia->getEstadoAnulado() == 0) {
                    if ($arGuia->getCodigoClienteFk() == $arFactura->getCodigoClienteFk()) {
                        $arGuia->setFacturaRel($arFactura);
                        $arGuia->setEstadoFacturaGenerada(1);
                        $em->persist($arGuia);
                        $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->retencionTransporte();
                        $arFacturaDetalle = new TteFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setGuiaRel($arGuia);
                        $arFacturaDetalle->setVrDeclara($arGuia->getVrDeclara());
                        $arFacturaDetalle->setVrFlete($arGuia->getVrFlete());
                        $arFacturaDetalle->setVrManejo($arGuia->getVrManejo());
                        $arFacturaDetalle->setUnidades($arGuia->getUnidades());
                        $arFacturaDetalle->setPesoReal($arGuia->getPesoReal());
                        $arFacturaDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                        $arFacturaDetalle->setFacturaPlanillaRel($arFacturaPlanilla);
                        $arFacturaDetalle->setCodigoImpuestoRetencionFk($arrConfiguracion['codigoImpuestoRetencionTransporteFk']);
                        $em->persist($arFacturaDetalle);

                        $arFactura->setGuias($arFactura->getGuias() + 1);
                        $arFactura->setVrFlete($arFactura->getVrFlete() + $arGuia->getVrFlete());
                        $arFactura->setVrManejo($arFactura->getVrManejo() + $arGuia->getVrManejo());
                        $subtotal = $arFactura->getVrSubtotal() + $arGuia->getVrFlete() + $arGuia->getVrManejo();
                        $arFactura->setVrSubtotal($subtotal);
                        $arFactura->setVrTotal($subtotal);
                        $em->persist($arFactura);

                        $arFacturaPlanilla->setGuias($arFacturaPlanilla->getGuias() + 1);
                        $arFacturaPlanilla->setVrFlete($arFacturaPlanilla->getVrFlete() + $arGuia->getVrFlete());
                        $arFacturaPlanilla->setVrManejo($arFacturaPlanilla->getVrManejo() + $arGuia->getVrManejo());
                        $subtotal = $arFacturaPlanilla->getVrTotal() + $arGuia->getVrFlete() + $arGuia->getVrManejo();
                        $arFacturaPlanilla->setVrTotal($subtotal);
                        $em->persist($arFacturaPlanilla);

                        $em->flush();
                        return [
                            'error' => false,
                            'mensaje' => '',
                        ];
                    } else {
                        return [
                            'error' => true,
                            'mensaje' => 'La guia es de otro cliente y no se puede adicionar a la factura ',
                        ];
                    }
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede ser una factura de venta y no puede estar anulada',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia ya esta prefacturada o facturada en la factura ' . $arGuia->getCodigoFacturaFk(),
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " o la factura planilla" . $codigoFacturaPlanilla . " no existe ",
            ];
        }
    }


    /**
     * @param $codigoCumplido $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiCumplidoAdicionar($codigoCumplido, $codigoGuia, $documento, $tipo)
    {
        $em = $this->getEntityManager();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $arGuia = NULL;

        if ($tipo == 1) {
            $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        }
        if ($tipo == 2) {
            $arGuiaDocumento = $em->getRepository(TteGuia::class)->findOneBy(array(
                'documentoCliente' => $documento,
                'codigoClienteFk' => $arCumplido->getCodigoClienteFk(),
                'estadoCumplido' => 0));
            if ($arGuiaDocumento) {
                $arGuia = $em->getRepository(TteGuia::class)->find($arGuiaDocumento->getCodigoGuiaPk());
            } else {
                $arGuia = "";
            }
        }

        if ($arGuia && $arCumplido) {
            if ($arGuia->getEstadoCumplido() == 0) {
                if ($arGuia->getEstadoAnulado() == 0) {
                    if ($arGuia->getCodigoClienteFk() == $arCumplido->getCodigoClienteFk()) {
                        $arGuia->setCumplidoRel($arCumplido);
                        $arGuia->setEstadoCumplido(1);
                        $em->persist($arGuia);

                        //$arCumplido->setUnidades($arCumplido->getUnidades() + $arGuia->getUnidades());
                        //$arCumplido->setPesoReal($arCumplido->getPesoReal() + $arGuia->getPesoReal());
                        //$arCumplido->setPesoVolumen($arCumplido->getPesoVolumen() + $arGuia->getPesoVolumen());
                        $arCumplido->setCantidad($arCumplido->getCantidad() + 1);
                        $em->persist($arCumplido);
                        $em->flush();
                        return [
                            'error' => false,
                            'mensaje' => '',
                        ];
                    } else {
                        return [
                            'error' => true,
                            'mensaje' => 'La guia es de otro cliente y no se puede adicionar al cumplido ',
                        ];
                    }
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede estar anulada',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia ya esta en los cumplidos ' . $arGuia->getCodigoCumplidoFk(),
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " o el cumplido " . $codigoCumplido . " no existe pendiente para cumplido",
            ];
        }
    }

    public function tableroProduccionMes($fecha): array
    {
        $em = $this->getEntityManager();
        $fecha->modify('first day of this month');
        $fechaDesde = $fecha->format('Y-m-d');
        $fecha->modify('last day of this month');
        $fechaHasta = $fecha->format('Y-m-d');
        $sql = "SELECT DAY(fecha_ingreso) as dia, 
                SUM(vr_flete) as flete, 
                SUM(vr_manejo) as manejo, 
                SUM(vr_flete + vr_manejo) as total,
                SUM(peso_real) as pesoReal 
                FROM tte_guia 
                WHERE fecha_ingreso >= '" . $fechaDesde . " 00:00:00' AND fecha_ingreso <='" . $fechaHasta . " 23:59:59'  
                GROUP BY DAY(fecha_ingreso)";
        $connection = $em->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function tableroProduccionAnio($fecha): array
    {
        $em = $this->getEntityManager();

        $fechaDesde = $fecha->format('Y') . "-01-01";
        $fechaHasta = $fecha->format('Y') . "-12-31";
        $sql = "SELECT MONTH(fecha_ingreso) as dia, 
                SUM(vr_flete) as flete, 
                SUM(vr_manejo) as manejo, 
                SUM(vr_flete + vr_manejo) as total, 
                SUM(peso_Real) as pesoReal
                FROM tte_guia 
                WHERE fecha_ingreso >= '" . $fechaDesde . " 00:00:00' AND fecha_ingreso <='" . $fechaHasta . " 23:59:59'  
                GROUP BY MONTH(fecha_ingreso)";
        $connection = $em->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function correccionGuia()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.codigoServicioFk')
            ->addSelect('tg.codigoGuiaTipoFk')
            ->addSelect('tg.numero')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('tg.codigoOperacionIngresoFk')
            ->addSelect('tg.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('tg.remitente')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.vrManejo')
            ->addSelect('tg.vrRecaudo')
            ->addSelect('tg.vrDeclara')
            ->addSelect('tg.estadoImpreso')
            ->addSelect('tg.estadoAutorizado')
            ->addSelect('tg.estadoAnulado')
            ->addSelect('tg.estadoAprobado')
            ->addSelect('tg.estadoEmbarcado')
            ->addSelect('tg.estadoDespachado')
            ->addSelect('tg.estadoEntregado')
            ->addSelect('tg.estadoSoporte')
            ->addSelect('tg.estadoCumplido')
            ->addSelect('tg.estadoFacturado')
            ->addSelect('tg.estadoNovedad')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->where('tg.estadoFacturaGenerada = 0')
            ->andWhere('tg.estadoAnulado = 0');
        if ($session->get('filtroTteGuiaDocumento') != "") {
            $queryBuilder->andWhere("tg.documentoCliente LIKE '%" . $session->get('filtroTteGuiaDocumento') . "%'");
        }
        if ($session->get('filtroTteGuiaNumero') != "") {
            $queryBuilder->andWhere("tg.numero = " . $session->get('filtroTteGuiaNumero'));
        }
        if ($session->get('filtroTteGuiaCodigo') != "") {
            $queryBuilder->andWhere("tg.codigoGuiaPk = " . $session->get('filtroTteGuiaCodigo'));
        }
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        $queryBuilder->orderBy('tg.fechaIngreso', 'DESC');
        return $queryBuilder;
    }

    /**
     * @return mixed
     */
    public function listaContabilizarRecaudo()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrRecaudo')
            ->leftJoin('g.clienteRel', 'c')
            ->where('g.estadoContabilizadoRecaudo =  0')
            ->andWhere('g.vrRecaudo > 0')
            ->orderBy('g.fechaIngreso', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("g.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("g.fechaIngreso >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("g.fechaIngreso <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @return mixed
     */
    public function informeFacturaPendiente()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.codigoServicioFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.estadoFacturaGenerada = 0')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.factura = 0')
            ->andWhere('g.cortesia = 0')
            ->orderBy('c.nombreCorto', 'ASC')
            ->addOrderBy('g.fechaIngreso', 'ASC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("g.fechaIngreso >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("g.fechaIngreso <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    /**
     * @return mixed
     */
    public function informeFacturaPendienteCliente()
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.codigoClienteFk')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.codigoServicioFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.estadoFacturaGenerada = 0')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.factura = 0')
            ->andWhere('g.cortesia = 0')
            ->orderBy('g.codigoClienteFk', 'ASC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("g.fechaIngreso >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("g.fechaIngreso <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    /**
     * @param $codigoGuia
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function guiaCliente($codigoGuia)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('c.codigoIdentificacionFk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.codigoFormaPagoFk')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.plazoPago')
            ->addSelect('c.direccion')
            ->addSelect('c.telefono')
            ->addSelect('c.correo')
            ->leftJoin('g.clienteRel', 'c')
            ->where('g.codigoGuiaPk = ' . $codigoGuia);
        return $queryBuilder->getQuery()->getSingleResult();
    }

    /**
     * @param $arrCodigoGuia array
     */
    public function desembarco($arrCodigoGuia, $arOperacion)
    {
        $em = $this->_em;
        if ($arrCodigoGuia) {
            foreach ($arrCodigoGuia as $codigoGuia) {
                $arGuia = $em->find(TteGuia::class, $codigoGuia);
                if ($arGuia) {
                    if ($arGuia->getEstadoDespachado() && $arGuia->getCodigoDespachoFk() && !$arGuia->getEstadoAnulado() && !$arGuia->getEstadoSoporte() && !$arGuia->getEstadoEntregado()) {
                        $arDesembarco = new TteDesembarco();
                        $arGuia->setFechaDespacho(null);
                        $arGuia->setFechaCumplido(null);
                        $arGuia->setFechaEntrega(null);
                        $arGuia->setFechaSoporte(null);
                        $arGuia->setFechaDesembarco(new \DateTime('now'));
                        $arGuia->setEstadoDespachado(0);
                        $arGuia->setEstadoEmbarcado(0);
                        $arGuia->setEstadoEntregado(0);
                        $arGuia->setEstadoSoporte(0);
                        $arGuia->setOperacionCargoRel($arOperacion);
                        $arDesembarco->setDespachoRel($arGuia->getDespachoRel());
                        $arDesembarco->setGuiaRel($arGuia);
                        $arDesembarco->setFecha(new \DateTime('now'));
                        $arGuia->setCodigoDespachoFk(null);
                        $em->persist($arGuia);
                        $em->persist($arDesembarco);
                        $em->flush();
                    }
                }
            }
        }
    }

    /**
     * @param $codigoGuia
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function imprimirGuia($codigoGuia)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.remitente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.direccionDestinatario')
            ->addSelect('g.telefonoDestinatario')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.comentario')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrCobroEntrega')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.numero')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('gt.nombre AS guiaTipo')
            ->addSelect('c.codigoIdentificacionFk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.codigoFormaPagoFk')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.plazoPago')
            ->addSelect('c.direccion AS direccionCliente')
            ->addSelect('c.telefono AS telefonoCliente')
            ->addSelect('c.correo')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadOrigenRel', 'co')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.guiaTipoRel', 'gt')
            ->where('g.codigoGuiaPk = ' . $codigoGuia);
        return $queryBuilder->getQuery()->getSingleResult();
    }

    public function imprimirGuiaMasivo()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.remitente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.direccionDestinatario')
            ->addSelect('g.telefonoDestinatario')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.comentario')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.vrCobroEntrega')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.numero')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('gt.nombre AS guiaTipo')
            ->addSelect('c.codigoIdentificacionFk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.codigoFormaPagoFk')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.plazoPago')
            ->addSelect('c.direccion AS direccionCliente')
            ->addSelect('c.telefono AS telefonoCliente')
            ->addSelect('c.correo')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadOrigenRel', 'co')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.guiaTipoRel', 'gt')
            ->where('g.codigoGuiaPk <> 0');
        if ($session->get('filtroNumeroDesde') != "") {
            $queryBuilder->andWhere("g.numero >= " . $session->get('filtroNumeroDesde'));
        }
        if ($session->get('filtroNumeroHasta') != "") {
            $queryBuilder->andWhere("g.numero <= " . $session->get('filtroNumeroHasta'));
        }
        if ($session->get('filtroCodigoDespacho') != "") {
            $queryBuilder->andWhere("g.codigoDespachoFk = " . $session->get('filtroCodigoDespacho'));
        }
        return $queryBuilder;
    }

    public function entregaFecha(){
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TteGuia::class,'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.fechaEntrega')
            ->addSelect('dg.fechaRegistro')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.codigoDespachoFk')
            ->addSelect('(dg.numero) AS manifiesto')
            ->addSelect('ct.movil')
            ->addSelect('ct.nombreCorto AS conductor')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrFlete')
            ->addSelect('g.estadoNovedad')
            ->addSelect('g.estadoNovedadSolucion')
            ->addSelect('g.estadoCumplido')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.despachoRel', 'dg')
            ->leftJoin('dg.conductorRel', 'ct')
            ->where('g.estadoEntregado = 1')
            ->orderBy('g.fechaEntrega');
        if($session->get('filtroFechaEntregaDesde')){
            $queryBuilder->andWhere('g.fechaEntrega >= '."'{$session->get('filtroFechaEntregaDesde')}'");
        }
        if($session->get('filtroFechaEntregaHasta')){
            $queryBuilder->andWhere('g.fechaEntrega <= '."'{$session->get('filtroFechaEntregaHasta')}'");
        }
        return $queryBuilder;
    }

    public function soporteFecha(){
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TteGuia::class,'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.fechaSoporte')
            ->addSelect('dg.fechaRegistro')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.codigoDespachoFk')
            ->addSelect('(dg.numero) AS manifiesto')
            ->addSelect('ct.movil')
            ->addSelect('ct.nombreCorto AS conductor')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrFlete')
            ->addSelect('g.estadoNovedad')
            ->addSelect('g.estadoNovedadSolucion')
            ->addSelect('g.estadoCumplido')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.despachoRel', 'dg')
            ->leftJoin('dg.conductorRel', 'ct')
            ->where('g.estadoSoporte = 1')
            ->orderBy('g.fechaSoporte','DESC');
        if($session->get('filtroFechaSoporteDesde')){
            $queryBuilder->andWhere('g.fechaSoporte >= '."'{$session->get('filtroFechaSoporteDesde')}'");
        }
        if($session->get('filtroFechaSoporteHasta')){
            $queryBuilder->andWhere('g.fechaSoporte <= '."'{$session->get('filtroFechaSoporteHasta')}'");
        }
        return $queryBuilder;
    }

    public function estadoGuiaCliente(){
        $em = $this->getEntityManager();
        $session=new Session();
        $filtro=$session->get('notificarEstadoCliente')?"WHERE g.codigoClienteFk='{$session->get('notificarEstadoCliente')}'":"";
        $arGuiaEstado = $em->createQuery(
            "SELECT COUNT(g.codigoGuiaPk) as guias,
            g.codigoClienteFk,
                c.nombreCorto as cliente
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
            {$filtro}
        GROUP BY g.codigoClienteFk");
        return $arGuiaEstado->execute();
    }

    public function pendienteFacturarCliente(){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->addSelect('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.codigoClienteFk')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->addSelect('g.pesoVolumen')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.codigoServicioFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.estadoFacturaGenerada = 0')
            ->andWhere('g.estadoAnulado = 0')
            ->andWhere('g.factura = 0')
            ->andWhere('g.cortesia = 0')
            ->orderBy('g.codigoClienteFk', 'ASC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("g.fechaIngreso >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("g.fechaIngreso <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("g.fechaIngreso <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrGuias
     */
    public function actualizarNumeros($arrGuias){
        if($arrGuias){
            /** @var  $arGuia TteGuia */
            foreach ($arrGuias as $arGuia) {
                if($arGuia->getNumero() == 0){
                    $arGuia->setNumero($arGuia->getCodigoGuiaPk());
                    $this->_em->persist($arGuia);
                }
            }
            $this->_em->flush($arGuia);
        }
    }

}
