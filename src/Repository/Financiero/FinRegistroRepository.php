<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinRegistro;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class FinRegistroRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinRegistro::class);
    }

    public function registros()
    {

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.codigoRegistroPk AS id')
            ->addSelect('r.numero')
            ->addSelect('r.numeroPrefijo')
            ->addSelect('r.numeroReferencia')
            ->addSelect('r.numeroReferenciaPrefijo')
            ->addSelect('r.fecha')
            ->addSelect('r.codigoComprobanteFk AS idComprobante')
            ->addSelect('c.nombre AS comprobante')
            ->addSelect('r.codigoCuentaFk AS cuenta')
            ->addSelect('r.codigoCentroCostoFk AS c_c')
            ->addSelect('t.numeroIdentificacion AS nit')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('r.vrDebito')
            ->addSelect('r.vrCredito')
            ->addSelect('r.vrBase')
            ->addSelect('r.descripcion')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.comprobanteRel', 'c')
            ->where('r.codigoRegistroPk <> 0')
            ->orderBy('r.numero', 'DESC')
            ->addOrderBy('r.codigoComprobanteFk', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroFinCodigoTercero')) {
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroFinCodigoTercero')}");
        }
        if ($session->get('filtroFinComprobante') != '') {
            $queryBuilder->andWhere("r.codigoComprobanteFk = '{$session->get('filtroFinComprobante')}'");
        }
        if ($session->get('filtroFinNumeroDesde') != '') {
            $queryBuilder->andWhere("r.numero >= {$session->get('filtroFinNumeroDesde')}");
        }
        if ($session->get('filtroFinNumeroHasta') != '') {
            $queryBuilder->andWhere("r.numero <= {$session->get('filtroFinNumeroHasta')}");
        }
        if ($session->get('filtroFinNumeroPrefijo') != '') {
            $queryBuilder->andWhere("r.numeroPrefijo = '{$session->get('filtroFinNumeroPrefijo')}'");
        }
        if ($session->get('filtroFinCuenta') != '') {
            $queryBuilder->andWhere("r.codigoCuentaFk = '{$session->get('filtroFinCuenta')}'");
        }
        if ($session->get('filtroFinCentroCosto') != '') {
            $queryBuilder->andWhere("r.codigoCentroCostoFk = '{$session->get('filtroFinCentroCosto')}'");
        }
        if ($session->get('filtroFinNumeroReferencia') != '') {
            $queryBuilder->andWhere("r.numeroReferencia = {$session->get('filtroFinNumeroReferencia')}");
        }
        if ($session->get('filtroFinNumeroReferenciaPrefijo') != '') {
            $queryBuilder->andWhere("r.numeroReferenciaPrefijo = '{$session->get('filtroFinNumeroReferenciaPrefijo')}'");
        }
        if ($session->get('filtroFinRegistroFiltroFecha') == true) {
            if ($session->get('filtroFinRegistroFechaDesde') != null) {
                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroFinRegistroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFinRegistroFechaHasta') != null) {
                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroFinRegistroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function auxiliar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.codigoRegistroPk AS id')
            ->addSelect('r.numero')
            ->addSelect('r.codigoCuentaFk as cuenta')
            ->addSelect('r.numeroPrefijo')
            ->addSelect('r.codigoDocumento')
            ->addSelect('r.numeroReferencia')
            ->addSelect('r.numeroReferenciaPrefijo')
            ->addSelect('r.fecha')
            ->addSelect('r.codigoComprobanteFk AS idComprobante')
            ->addSelect('c.nombre AS comprobante')
            ->addSelect('cu.nombre AS cuentaNombre')
            ->addSelect('r.codigoCentroCostoFk AS c_c')
            ->addSelect('t.numeroIdentificacion AS nit')
            ->addSelect('t.nombreCorto')
            ->addSelect('r.vrDebito')
            ->addSelect('r.vrCredito')
            ->addSelect('r.vrBase')
            ->addSelect('r.descripcion')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.comprobanteRel', 'c')
            ->leftJoin('r.cuentaRel', 'cu')
            ->where('r.codigoRegistroPk <> 0')
            ->orderBy('r.codigoCuentaFk', 'DESC')
            ->addOrderBy('r.fecha', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroFinCodigoTercero')) {
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroFinCodigoTercero')}");
        }
        if ($session->get('filtroFinComprobante') != '') {
            $queryBuilder->andWhere("r.codigoComprobanteFk = '{$session->get('filtroFinComprobante')}'");
        }
        if ($session->get('filtroFinNumeroDesde') != '') {
            $queryBuilder->andWhere("r.numero >= {$session->get('filtroFinNumeroDesde')}");
        }
        if ($session->get('filtroFinNumeroHasta') != '') {
            $queryBuilder->andWhere("r.numero <= {$session->get('filtroFinNumeroHasta')}");
        }
        if ($session->get('filtroFinCuenta') != '') {
            $queryBuilder->andWhere("r.codigoCuentaFk = '{$session->get('filtroFinCuenta')}'");
        }
        if ($session->get('filtroFinCentroCosto') != '') {
            $queryBuilder->andWhere("r.codigoCentroCostoFk = {$session->get('filtroFinCentroCosto')}");
        }
        if ($session->get('filtroFinNumeroReferencia') != '') {
            $queryBuilder->andWhere("r.numeroReferencia = {$session->get('filtroFinNumeroReferencia')}");
        }
        if ($session->get('filtroFinRegistroFiltroFecha') == true) {
            if ($session->get('filtroFinRegistroFechaDesde') != null) {
                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroFinRegistroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFinRegistroFechaHasta') != null) {
                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroFinRegistroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function listaIntercambio()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.codigoRegistroPk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaVence')
            ->addSelect('r.numeroReferencia')
            ->addSelect('r.numeroPrefijo')
            ->addSelect('r.numeroReferenciaPrefijo')
            ->addSelect('r.fecha')
            ->addSelect('r.vrDebito')
            ->addSelect('r.vrCredito')
            ->addSelect('r.vrBase')
            ->addSelect('r.naturaleza')
            ->addSelect('r.codigoCentroCostoFk')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.codigoComprobanteFk')
            ->addSelect('r.codigoComprobanteReferenciaFk')
            ->addSelect('c.nombre')
            ->addSelect('r.descripcion')
            ->addSelect('r.codigoTerceroFk')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.direccion')
            ->addSelect('ct.codigoCuentaPk AS codigoCuenta')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.comprobanteRel', 'c')
            ->leftJoin('r.cuentaRel', 'ct')
        ->orderBy('r.codigoComprobanteFk')
        ->addOrderBy('r.numeroPrefijo')
        ->addOrderBy('r.numero');
        $fecha = new \DateTime('now');
        if ($session->get('filtroFinRegistrosTodos') == false) {
            $queryBuilder->andWhere("r.estadoIntercambio = 0");
        }
        if ($session->get('filtroFinCodigoTercero')) {
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroFinCodigoTercero')}");
        }
        if ($session->get('filtroFinComprobante') != '') {
            $queryBuilder->andWhere("r.codigoComprobanteFk = '{$session->get('filtroFinComprobante')}'");
        }
        if ($session->get('filtroFinNumeroDesde') != '') {
            $queryBuilder->andWhere("r.numero >= {$session->get('filtroFinNumeroDesde')}");
        }
        if ($session->get('filtroFinNumeroHasta') != '') {
            $queryBuilder->andWhere("r.numero <= {$session->get('filtroFinNumeroHasta')}");
        }
        if ($session->get('filtroFinNumeroPrefijo') != '') {
            $queryBuilder->andWhere("r.numeroPrefijo = '{$session->get('filtroFinNumeroPrefijo')}'");
        }
        if ($session->get('filtroFinCuenta') != '') {
            $queryBuilder->andWhere("r.codigoCuentaFk = {$session->get('filtroFinCuenta')}");
        }
        if ($session->get('filtroFinCentroCosto') != '') {
            $queryBuilder->andWhere("r.codigoCentroCostoFk = {$session->get('filtroFinCentroCosto')}");
        }
        if ($session->get('filtroFinCentroCosto') != '') {
            $queryBuilder->andWhere("r.codigoCentroCostoFk = {$session->get('filtroFinCentroCosto')}");
        }
        if ($session->get('filtroFinNumeroReferencia') != '') {
            $queryBuilder->andWhere("r.numeroReferencia = {$session->get('filtroFinNumeroReferencia')}");
        }
        if ($session->get('filtroFinNumeroReferenciaPrefijo') != '') {
            $queryBuilder->andWhere("r.numeroReferenciaPrefijo = '{$session->get('filtroFinNumeroReferenciaPrefijo')}'");
        }
        if ($session->get('filtroFinRegistroFiltroFecha') == true) {
            if ($session->get('filtroFinRegistroFechaDesde') != null) {
                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroFinRegistroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFinRegistroFechaHasta') != null) {
                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroFinRegistroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function aplicarIntercambio()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = "UPDATE App\Entity\Financiero\FinRegistro r set r.estadoIntercambio = 1 
                      WHERE r.estadoIntercambio = 0";
        if ($session->get('filtroFinComprobante') != '') {
            $dql .= " AND r.codigoComprobanteFk = {$session->get('filtroFinComprobante')}";
        }
        if ($session->get('filtroFinRegistroFiltroFecha') == true) {
            if ($session->get('filtroFinRegistroFechaDesde') != null) {
                $dql .= " AND r.fecha >= '{$session->get('filtroFinRegistroFechaDesde')} 00:00:00'";
            }
            if ($session->get('filtroFinRegistroFechaHasta') != null) {
                $dql .= " AND r.fecha <= '{$session->get('filtroFinRegistroFechaHasta')} 23:59:59'";
            }
        }
        $query = $em->createQuery($dql);
        $query->execute();

    }

    public function listaVerMovimiento($clase, $id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.codigoRegistroPk AS id')
            ->addSelect('r.numero')
            ->addSelect('r.numeroPrefijo')
            ->addSelect('r.numeroReferencia')
            ->addSelect('r.numeroReferenciaPrefijo')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaVence')
            ->addSelect('r.codigoComprobanteFk AS idComprobante')
            ->addSelect('c.nombre AS comprobante')
            ->addSelect('r.codigoCuentaFk AS cuenta')
            ->addSelect('r.codigoCentroCostoFk AS c_c')
            ->addSelect('t.numeroIdentificacion AS nit')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('r.vrDebito')
            ->addSelect('r.vrCredito')
            ->addSelect('r.vrBase')
            ->addSelect('r.descripcion')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.comprobanteRel', 'c')
            ->where("r.codigoModeloFk ='" . $clase . "'")
            ->andWhere('r.codigoDocumento = ' . $id)
            ->orderBy('r.codigoRegistroPk', 'ASC');
        return $queryBuilder;
    }

    public function analizarInconsistencias($fechaDesde, $fechaHasta){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.codigoComprobanteFk')
            ->addSelect('r.numero')
            ->addSelect('r.numeroPrefijo')
            ->addSelect('SUM(r.vrCredito) AS vrCredito')
            ->addSelect('SUM(r.vrDebito) AS vrDebito')
            ->where("r.fecha >= '" . $fechaDesde . " 00:00:00'")
            ->andWhere("r.fecha <= '" . $fechaHasta . " 23:59:59'")
            ->orderBy('r.numero')
            ->groupBy('r.codigoComprobanteFk')
        ->addGroupBy('r.numero')
        ->addGroupBy('r.numeroPrefijo');
        return $queryBuilder->getQuery()->getResult();
    }

    public function documentoPeriodo($fechaDesde, $fechaHasta, $codigoComprobante = null){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.codigoComprobanteFk')
            ->addSelect('r.numeroPrefijo')
            ->addSelect('MIN(r.numero) as minimo')
            ->addSelect('MAX(r.numero) as maximo')
            ->where("r.fecha >= '" . $fechaDesde . " 00:00:00'")
            ->andWhere("r.fecha <= '" . $fechaHasta . " 23:59:59'")
            ->groupBy('r.codigoComprobanteFk')
            ->addGroupBy('r.numeroPrefijo');
        if($codigoComprobante) {
            $queryBuilder->andWhere("r.codigoComprobanteFk = '" . $codigoComprobante . "'");
        }
        return $queryBuilder->getQuery()->getResult();
    }

    public function documentoPeriodoEncabezado($comprobante, $prefijo, $desde, $hasta){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.numero')
            ->where("r.numero >= " . $desde)
            ->andWhere("r.numero <= " . $hasta)
            ->andWhere("r.codigoComprobanteFk = '" . $comprobante . "'")
            ->groupBy('r.codigoComprobanteFk')
            ->addGroupBy('r.numeroPrefijo')
            ->addGroupBy("r.numero")
        ->orderBy('r.numero');
        if($prefijo) {
            $queryBuilder->andWhere("r.numeroPrefijo = '" . $prefijo . "'");
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(FinRegistro::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();
    }
}