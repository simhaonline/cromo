<?php

namespace App\Repository\Tesoreria;

use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TesCuentaPagarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TesCuentaPagar::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCuentaPagar = null;
        $numero = null;
        $codigoModelo = null;
        $modelo = null;
        $numero = null;
        $codigoTercero = null;
        $cuentaPagarTipo = null;
        $cuentaPagarBanco = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCuentaPagar = $filtros['codigoCuentaPagar'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $codigoModelo = $filtros['codigoModelo'] ?? null;
            $modelo = $filtros['modelo'] ?? null;
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $cuentaPagarTipo = $filtros['cuentaPagarTipo'] ?? null;
            $cuentaPagarBanco = $filtros['cuentaPagarBanco'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cpt.nombre AS tipo')
            ->addSelect('cpb.nombre AS banco')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrSaldoOriginal')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.vrSaldoOperado')
            ->addSelect('cp.estadoAutorizado')
            ->addSelect('cp.estadoAprobado')
            ->addSelect('cp.estadoAnulado')
            ->leftJoin('cp.terceroRel', 't')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->leftJoin('cp.bancoRel', 'cpb');
        if ($codigoCuentaPagar) {
            $queryBuilder->andWhere("cp.codigoCuentaPagarPk = '{$codigoCuentaPagar}'");
        }
        if($numero) {
            $queryBuilder->andWhere("cp.numeroDocumento = '{$numero}'");
        }
        if($codigoModelo) {
            $queryBuilder->andWhere("cp.codigoDocumento = '{$codigoModelo}'");
        }
        if($modelo) {
            $queryBuilder->andWhere("cp.modelo = '{$modelo}'");
        }
        if ($codigoTercero) {
            $queryBuilder->andWhere("cp.codigoTerceroFk = '{$codigoTercero}'");
        }
        if ($cuentaPagarTipo) {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk = '{$cuentaPagarTipo}'");
        }
        if ($cuentaPagarBanco) {
            $queryBuilder->andWhere("cp.codigoBancoFk = '{$cuentaPagarBanco}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("cp.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("cp.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("cp.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cp.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("cp.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cp.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("cp.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cp.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('cp.codigoCuentaPagarPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function pendientes()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->addSelect('cp.numeroReferencia')
            ->addSelect('cpt.nombre AS tipo')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
            ->addSelect('cp.soporte')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.nombreCorto')
            ->addSelect('cp.plazo')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.vrSaldoOriginal')
            ->addSelect('cp.vrSaldo')
            ->addSelect('cp.vrSaldoOperado')
            ->addSelect('cp.comentario')
            ->leftJoin('cp.terceroRel', 't')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->where('cp.codigoCuentaPagarPk <> 0')
            ->andWhere('cp.vrSaldo > 0')
            ->orderBy('t.nombreCorto', 'ASC')
            ->addOrderBy('cp.fecha', 'ASC');

        $fecha = new \DateTime('now');
        if ($session->get('filtroTesCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk in ({$session->get('filtroTesCuentaPagarTipo')})");
        }
        if ($session->get('filtroTesNumeroReferencia') != '') {
            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroTesNumeroReferencia')}");
        }
        if ($session->get('filtroTesCuentaPagarNumero') != '') {
            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroTesCuentaPagarNumero')}");
        }
        if ($session->get('filtroTesCodigoTercero')) {
            $queryBuilder->andWhere("cp.codigoTerceroFk = {$session->get('filtroTesCodigoTercero')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("cp.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("cp.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("cp.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("cp.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function cuentasPagar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.numeroReferencia')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrSaldo')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.plazo')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
            ->addSelect('cp.estadoVerificado')
            ->addSelect('cpt.nombre as cuentaPagarTipoNombre')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->addSelect('t.numeroIdentificacion as terceroNumeroIdentificacion')
            ->join('cp.terceroRel', 't')
            ->join('cp.cuentaPagarTipoRel', 'cpt')
            ->where('cp.vrSaldo <> 0')
            ->andWhere('cp.operacion = 1')
            ->orderBy('cp.codigoCuentaPagarPk', 'ASC');

        if ($session->get('filtroTesCodigoTercero') != "") {
            $queryBuilder->andWhere("cp.codigoTerceroFk = {$session->get('filtroTesCodigoTercero')}");
        }
        if ($session->get('filtroTesCuentaPagarNumero') != "") {
            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroTesCuentaPagarNumero')}");
        }
        if ($session->get('filtroTesCuentaPagarNumeroReferencia') != "") {
            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroTesCuentaPagarNumeroReferencia')}");
        }
        if ($session->get('filtroTesCuentaPagarCodigo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarPk = {$session->get('filtroTesCuentaPagarCodigo')}");
        }
        if ($session->get('filtroTesCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk = '{$session->get('filtroTesCuentaPagarTipo')}'");
        }

        if ($session->get('filtroGenBanco') != "") {
            $queryBuilder->andWhere("cp.codigoBancoFk = {$session->get('filtroGenBanco')}");
        }

        if ($session->get('filtroTesFechaDesde') != null) {
            $queryBuilder->andWhere("cp.fecha >= '{$session->get('filtroTesFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTesFechaHasta') != null) {
            $queryBuilder->andWhere("cp.fecha <= '{$session->get('filtroTesFechaHasta')} 23:59:59'");
        }


        return $queryBuilder;
    }

    public function cuentasPagarDetalleNuevo($codigoTercero)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.numeroReferencia')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrSaldo')
            ->addSelect('cp.vrSaldoOperado')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.plazo')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
            ->addSelect('cp.estadoVerificado')
            ->addSelect('cpt.nombre as cuentaPagarTipoNombre')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->addSelect('t.numeroIdentificacion as terceroNumeroIdentificacion')
            ->join('cp.terceroRel', 't')
            ->join('cp.cuentaPagarTipoRel', 'cpt')
            ->where('cp.vrSaldo > 0')
            ->orderBy('cp.codigoCuentaPagarPk', 'ASC');


        if ($session->get('filtroTesCuentaPagarTodosTerceros')) {
            if ($session->get('filtroTesCodigoTercero') != "") {
                $queryBuilder->andWhere("cp.codigoTerceroFk = {$session->get('filtroTesCodigoTercero')}");
            }
        } else {
            $queryBuilder->andWhere("cp.codigoTerceroFk = {$codigoTercero}");
        }

        if ($session->get('filtroTesCuentaPagarNumero') != "") {
            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroTesCuentaPagarNumero')}");
        }
        if ($session->get('filtroTesCuentaPagarNumeroReferencia') != "") {
            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroTesCuentaPagarNumeroReferencia')}");
        }
        if ($session->get('filtroTesCuentaPagarCodigo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarPk = {$session->get('filtroTesCuentaPagarCodigo')}");
        }
        if ($session->get('filtroTesCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk = '{$session->get('filtroTesCuentaPagarTipo')}'");
        }

        if ($session->get('filtroGenBanco') != "") {
            $queryBuilder->andWhere("cp.codigoBancoFk = {$session->get('filtroGenBanco')}");
        }

        if ($session->get('filtroTesFechaDesde') != null) {
            $queryBuilder->andWhere("cp.fecha >= '{$session->get('filtroTesFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTesFechaHasta') != null) {
            $queryBuilder->andWhere("cp.fecha <= '{$session->get('filtroTesFechaHasta')} 23:59:59'");
        }


        return $queryBuilder;
    }

    public function generarVencimientos()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.fechaVence')
            ->where('cp.vrSaldo <> 0')
            ->andWhere('cp.operacion = 1');
        $arCuentasPagar = $queryBuilder->getQuery()->getResult();
        $fecha = new \DateTime('now');
        foreach ($arCuentasPagar AS $arCuentaPagar) {
            $arCuentaPagarAct = $em->getRepository(TesCuentaPagar::class)->find($arCuentaPagar['codigoCuentaPagarPk']);
            $interval = date_diff($arCuentaPagar['fechaVence'], $fecha);
            $dias = $interval->format('%r%a');
            $arCuentaPagarAct->setDiasVencimiento($dias);
            $rango = 1;
            if ($dias <= 0) {
                $rango = 1;
            } else {
                if ($dias <= 30) {
                    $rango = 2;
                } else {
                    if ($dias <= 60) {
                        $rango = 3;
                    } else {
                        if ($dias <= 90) {
                            $rango = 4;
                        } else {
                            if ($dias <= 180) {
                                $rango = 5;
                            } else {
                                $rango = 6;
                            }
                        }
                    }
                }
            }
            $arCuentaPagarAct->setRango($rango);
            $em->persist($arCuentaPagarAct);
        }
        $em->flush();
    }

    public function carteraEdades()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.codigoTerceroFk')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrSaldoOperado')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.diasVencimiento')
            ->addSelect('cp.plazo')
            ->addSelect('cp.rango')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
            ->addSelect('t.nombreCorto as terceroNombre')
            ->addSelect('t.numeroIdentificacion')
            ->join('cp.terceroRel', 't')
            ->where('cp.vrSaldo <> 0')
            ->orderBy('t.nombreCorto', 'ASC')
            ->addOrderBy('cp.rango', 'DESC')
            ->addOrderBy('cp.fecha', 'ASC')
            ->addOrderBy('cp.numeroDocumento', 'ASC');

        $fecha = new \DateTime('now');
        if ($session->get('filtroTesCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk in ({$session->get('filtroTesCuentaPagarTipo')})");
        }
        if ($session->get('filtroTesNumeroReferencia') != '') {
            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroTesNumeroReferencia')}");
        }
        if ($session->get('filtroTesCuentaPagarNumero') != '') {
            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroTesCuentaPagarNumero')}");
        }
        if ($session->get('filtroTesCodigoTercero')) {
            $queryBuilder->andWhere("cp.codigoTerceroFk = {$session->get('filtroTesCodigoTercero')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("cp.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("cp.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("cp.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("cp.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder;
    }

    public function estadoCuenta()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.codigoTerceroFk')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrSaldoOperado')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.diasVencimiento')
            ->addSelect('cp.plazo')
            ->addSelect('cp.rango')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
            ->addSelect('t.nombreCorto as terceroNombre')
            ->addSelect('t.numeroIdentificacion')
            ->join('cp.terceroRel', 't')
            ->where('cp.vrSaldo <> 0')
            ->orderBy('t.nombreCorto', 'ASC')
            ->addOrderBy('cp.fecha', 'ASC');

        $fecha = new \DateTime('now');
        if ($session->get('filtroTesCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk in ({$session->get('filtroTesCuentaPagarTipo')})");
        }
        if ($session->get('filtroTesNumeroReferencia') != '') {
            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroTesNumeroReferencia')}");
        }
        if ($session->get('filtroTesCuentaPagarNumero') != '') {
            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroTesCuentaPagarNumero')}");
        }
        if ($session->get('filtroTesCodigoTercero')) {
            $queryBuilder->andWhere("cp.codigoTerceroFk = {$session->get('filtroTesCodigoTercero')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("cp.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("cp.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("cp.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("cp.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder;
    }

    /**
     * @param $arCuentaPagar TesCuentaPagar
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arCuentaPagar)
    {
        $em = $this->getEntityManager();
        if ($arCuentaPagar->getEstadoAprobado() == 1 && $arCuentaPagar->getEstadoAnulado() == 0) {
            if ($arCuentaPagar->getVrAbono() <= 0) {
                $arCuentaPagar->setEstadoAnulado(1);
                $arCuentaPagar->setVrSubtotal(0);
                $arCuentaPagar->setVrIva(0);
                $arCuentaPagar->setVrRetencionFuente(0);
                $arCuentaPagar->setVrRetencionIva(0);
                $arCuentaPagar->setVrTotal(0);
                $arCuentaPagar->setVrSaldo(0);
                $arCuentaPagar->setVrSaldoOriginal(0);
                $arCuentaPagar->setVrSaldoOperado(0);
                $em->persist($arCuentaPagar);
                $em->flush();
                return true;
            } else {
                Mensajes::error("La cuenta por pagar tiene abonos y no se puede anular");
                return false;
            }
        }
    }

    public function verificar($arCuentaPagar)
    {
        $em = $this->getEntityManager();
        if (!$arCuentaPagar->getEstadoVerificado()) {
            $arCuentaPagar->setEstadoVerificado(1);
            $em->persist($arCuentaPagar);
            $em->flush();
        }
    }

    public function anularExterno($codigoModelo, $codigoDocumento)
    {
        $em = $this->getEntityManager();
        $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->findOneBy(['modelo' => $codigoModelo, 'codigoDocumento' => $codigoDocumento]);
        if ($arCuentaPagar) {
            return $em->getRepository(TesCuentaPagar::class)->anular($arCuentaPagar);
        } else {
            return true;
        }
    }

    public function corregirSaldos()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.vrSaldoOriginal')
            ->addSelect('cp.operacion');
        //->where('cc.codigoCuentaCobrarPk=531');
        $arCuentasPagar = $queryBuilder->getQuery()->getResult();
        foreach ($arCuentasPagar as $arCuentaPagar) {
            $abonos = 0;
            $queryBuilder = $em->createQueryBuilder()->from(TesMovimientoDetalle::class, 'md')
                ->Select("SUM(md.vrPago) as vrPago")
                ->leftJoin('md.movimientoRel', 'i')
                ->where("md.codigoCuentaPagarFk = " . $arCuentaPagar['codigoCuentaPagarPk'])
                ->andWhere('i.estadoAutorizado = 1');
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if ($arrResultado['vrPago']) {
                    $abonos += $arrResultado['vrPago'];
                }
            }

            $saldo = $arCuentaPagar['vrSaldoOriginal'] - $abonos;
            $saldoOperado = $saldo * $arCuentaPagar['operacion'];
            $arCuentaPagarAct = $em->getRepository(TesCuentaPagar::class)->find($arCuentaPagar['codigoCuentaPagarPk']);
            $arCuentaPagarAct->setVrSaldo($saldo);
            $arCuentaPagarAct->setVrSaldoOperado($saldoOperado);
            $arCuentaPagarAct->setVrAbono($abonos);
            $em->persist($arCuentaPagarAct);
        }
        $em->flush();
        return true;
    }

}
