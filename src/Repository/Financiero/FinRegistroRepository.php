<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinRegistro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FinRegistroRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
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
        if ($session->get('filtroFinCuenta') != '') {
            $queryBuilder->andWhere("r.codigoCuentaFk = '{$session->get('filtroFinCuenta')}'");
        }
        if ($session->get('filtroFinCentroCosto') != '') {
            $queryBuilder->andWhere("r.codigoCentroCostoFk = '{$session->get('filtroFinCentroCosto')}'");
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
            $queryBuilder->andWhere("r.codigoComprobanteFk = {$session->get('filtroFinComprobante')}");
        }
        if ($session->get('filtroFinNumeroDesde') != '') {
            $queryBuilder->andWhere("r.numero >= {$session->get('filtroFinNumeroDesde')}");
        }
        if ($session->get('filtroFinNumeroHasta') != '') {
            $queryBuilder->andWhere("r.numero <= {$session->get('filtroFinNumeroHasta')}");
        }
        if ($session->get('filtroFinCuenta') != '') {
            $queryBuilder->andWhere("r.codigoCuentaFk = {$session->get('filtroFinCuenta')}");
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
            ->addSelect('c.nombre')
            ->addSelect('r.descripcion')
            ->addSelect('r.codigoTerceroFk')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.nombreCorto')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.comprobanteRel', 'c');
        $fecha = new \DateTime('now');
        if ($session->get('filtroFinRegistrosTodos') == false) {
            $queryBuilder->andWhere("r.estadoIntercambio = 0");
        }
        if ($session->get('filtroFinCodigoTercero')) {
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroFinCodigoTercero')}");
        }
        if ($session->get('filtroFinComprobante') != '') {
            $queryBuilder->andWhere("r.codigoComprobanteFk = {$session->get('filtroFinComprobante')}");
        }
        if ($session->get('filtroFinNumeroDesde') != '') {
            $queryBuilder->andWhere("r.numero >= {$session->get('filtroFinNumeroDesde')}");
        }
        if ($session->get('filtroFinNumeroHasta') != '') {
            $queryBuilder->andWhere("r.numero <= {$session->get('filtroFinNumeroHasta')}");
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
            ->orderBy('r.numero', 'DESC')
            ->addOrderBy('r.codigoComprobanteFk', 'DESC');
        return $queryBuilder;
    }
}