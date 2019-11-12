<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCompromisoDetalle;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Cartera\CarNotaCreditoDetalle;
use App\Entity\Cartera\CarNotaDebitoDetalle;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CarCuentaCobrarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarCuentaCobrar::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $numeroDocumento = null;
        $numeroReferencia =  null;
        $codigoCuentaCobrar =  null;
        $codigoCliente =  null;
        $codigoCuentaCobrarTipo =  null;
        $fechaDesde = null;
        $fechaHasta =  null;
        $estadoAutorizado =  null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        if ($filtros) {
            $numeroDocumento = $filtros['numeroDocumento'] ?? null;
            $numeroReferencia = $filtros['numeroReferencia'] ?? null;
            $codigoCuentaCobrar = $filtros['codigoCuentaCobrar'] ?? null;
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $codigoCuentaCobrarTipo = $filtros['codigoCuentaCobrarTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->leftJoin('cc.clienteRel', 'cl')
            ->leftJoin('cc.cuentaCobrarTipoRel', 'cct')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.codigoCuentaCobrarTipoFk')
            ->addSelect('cc.numeroReferencia')
            ->addSelect('cct.nombre AS tipo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cc.soporte')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto')
            ->addSelect('cc.plazo')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrSaldoOperado')
            ->addSelect('cc.estadoAnulado')
            ->addSelect('cc.estadoAprobado')
            ->addSelect('cc.estadoAutorizado')
            ->where('cc.codigoCuentaCobrarPk <> 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'DESC');
        
        if ($codigoCuentaCobrarTipo) {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk in ('{$codigoCuentaCobrarTipo}')");
        }
        
        if ($numeroDocumento) {
            $queryBuilder->andWhere("cc.numeroDocumento = '{$numeroDocumento}'");
        }
        
        if ($numeroReferencia) {
            $queryBuilder->andWhere("cc.numeroReferencia= '{$numeroReferencia}'");
        }
        
        if ($codigoCliente) {
            $queryBuilder->andWhere("cc.codigoClienteFk = {$codigoCliente}");
        }

        if ($codigoCuentaCobrar) {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarPk = {$codigoCuentaCobrar}");
        }

        if ($fechaDesde) {
            $queryBuilder->andWhere("cc.fecha >= '{$fechaDesde} 00:00:00'");
        }
        
        if ($fechaHasta) {
            $queryBuilder->andWhere("cc.fecha <= '{$fechaHasta} 23:59:59'");
        }
        
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("cc.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cc.estadoAutorizado = 1");
                break;
        }
        
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("cc.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cc.estadoAprobado = 1");
                break;
        }
        
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("cc.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cc.estadoAnulado = 1");
                break;
        }
        
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    /**
     * @param $arrSeleccionados
     */
    public function eliminar($arrSeleccionados)
    {

    }

    public function pendientes()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.codigoCuentaCobrarTipoFk')
            ->addSelect('cc.numeroReferencia')
            ->addSelect('cct.nombre AS tipo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('a.nombre AS asesor')
            ->addSelect('cc.soporte')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto')
            ->addSelect('cc.plazo')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldoOriginal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrSaldoOperado')
            ->addSelect('cc.comentario')
            ->leftJoin('cc.asesorRel', 'a')
            ->leftJoin('cc.clienteRel', 'cl')
            ->leftJoin('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.codigoCuentaCobrarPk <> 0')
            ->andWhere('cc.vrSaldo > 0')
            ->orderBy('cl.nombreCorto', 'ASC')
            ->addOrderBy('cc.fecha', 'ASC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk in ({$session->get('filtroCarCuentaCobrarTipo')})");
        }
        if ($session->get('filtroCarNumeroReferencia') != '') {
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if ($session->get('filtroCarCuentaCobrarNumero') != '') {
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if ($session->get('filtroCarCodigoCliente')) {
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("cc.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("cc.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("cc.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("cc.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("cc.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function cuentasCobrar($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrRetencionFuente')
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre')
            ->join('cc.clienteRel', 'cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->andWhere('cc.operacion = 1')
            ->andWhere('cc.codigoClienteFk = ' . $codigoCliente)
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    public function cuentasCobrarCompromiso($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre')
            ->join('cc.clienteRel', 'cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->andWhere('cc.operacion = 1')
            ->andWhere('cc.codigoClienteFk = ' . $codigoCliente)
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    /**
     * @param $arCuentaCobrar CarCuentaCobrar
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function cuentasCobrarAplicar($arCuentaCobrar)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.numeroReferencia')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre')
            ->join('cc.clienteRel', 'cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo > 0')
            ->andWhere('cc.operacion = -1')
            ->andWhere('cc.codigoClienteFk = ' . $arCuentaCobrar->getCodigoClienteFk())
            ->andWhere('cc.codigoCuentaCobrarPk <> ' . $arCuentaCobrar->getCodigoCuentaCobrarPk())
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    /**
     * @param $arCuentaCobrar CarCuentaCobrar
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function cuentasCobrarAplicarRecibo($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.numeroReferencia')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre')
            ->join('cc.clienteRel', 'cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo > 0')
            ->andWhere('cc.operacion = -1')
            ->andWhere('cc.codigoClienteFk = ' . $codigoCliente)
            ->andWhere('cc.anticipo = 1')
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    public function prueba($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cl.codigoClientePk')
            ->addSelect('cct.codigoCuentaCobrarTipoPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cc.plazo')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrSaldoOperado')
            ->addSelect('cc.soporte')
            ->where(TO_DAYS(NOW()) - TO_DAYS('cc.fechaVence AS diasVenciada'
                ))
            ->addSelect(CASE_WHEN(TO_DAYS(NOW()) - TO_DAYS('cc.fechaVence') < 1))
            ->join('cc.clienteRel', 'cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo > 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    public function generarVencimientos()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.fechaVence')
            ->where('cc.vrSaldo <> 0')
            ->andWhere('cc.operacion = 1');
        $arCuentasCobrar = $queryBuilder->getQuery()->getResult();
        $fecha = new \DateTime('now');
        foreach ($arCuentasCobrar AS $arCuentaCobrar) {
            $arCuentaCobrarAct = $em->getRepository(CarCuentaCobrar::class)->find($arCuentaCobrar['codigoCuentaCobrarPk']);
            $interval = date_diff($arCuentaCobrar['fechaVence'], $fecha);
            $dias = $interval->format('%r%a');
            $arCuentaCobrarAct->setDiasVencimiento($dias);
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
            $arCuentaCobrarAct->setRango($rango);
            $em->persist($arCuentaCobrarAct);
        }
        $em->flush();
    }

    public function carteraEdadesCliente()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.codigoClienteFk')
            ->addSelect('cc.codigoCuentaCobrarTipoFk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldoOperado')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.diasVencimiento')
            ->addSelect('cc.plazo')
            ->addSelect('cc.rango')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('c.nombreCorto as clienteNombre')
            ->addSelect('c.numeroIdentificacion')
            ->join('cc.clienteRel', 'c')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->orderBy('c.nombreCorto', 'ASC')
            ->addOrderBy('cc.rango', 'DESC')
            ->addOrderBy('cc.fecha', 'ASC')
            ->addOrderBy('cc.numeroDocumento', 'ASC');

        $fecha = new \DateTime('now');
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk in ({$session->get('filtroCarCuentaCobrarTipo')})");
        }
        if ($session->get('filtroCarNumeroReferencia') != '') {
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if ($session->get('filtroCarCuentaCobrarNumero') != '') {
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if ($session->get('filtroCarCodigoCliente')) {
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("cc.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("cc.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("cc.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("cc.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function estadoCuenta()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.codigoClienteFk')
            ->addSelect('cc.codigoCuentaCobrarTipoFk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldoOperado')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.diasVencimiento')
            ->addSelect('cc.plazo')
            ->addSelect('cc.rango')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('c.nombreCorto as clienteNombre')
            ->addSelect('c.numeroIdentificacion')
            ->join('cc.clienteRel', 'c')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->orderBy('c.nombreCorto', 'ASC')
            ->addOrderBy('cc.fecha', 'ASC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk in ({$session->get('filtroCarCuentaCobrarTipo')})");
        }
        if ($session->get('filtroCarNumeroReferencia') != '') {
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if ($session->get('filtroCarCuentaCobrarNumero') != '') {
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if ($session->get('filtroCarCodigoCliente')) {
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("cc.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("cc.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("cc.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("cc.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("cc.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function anularExterno($modulo, $codigoDocumento)
    {
        $respuesta = true;
        $em = $this->getEntityManager();
        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->findOneBy(array('modulo' => $modulo, 'codigoDocumento' => $codigoDocumento));
        if ($arCuentaCobrar) {
            $arRecibosDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoCuentaCobrarFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
            if ($arRecibosDetalles) {
                Mensajes::error("La cuenta por cobrar enlazada a este documento tiene recibos de caja, no se puede anular el documento");
                $respuesta = false;
            } else {
                $arCuentaCobrarAct = $em->getRepository(CarCuentaCobrar::class)->find($arCuentaCobrar->getCodigoCuentaCobrarPk());
                $arCuentaCobrarAct->setVrSubtotal(0);
                $arCuentaCobrarAct->setVrTotal(0);
                $arCuentaCobrarAct->setVrIva(0);
                $arCuentaCobrarAct->setVrRetencionFuente(0);
                $arCuentaCobrarAct->setVrRetencionIva(0);
                $arCuentaCobrarAct->setVrSaldoOriginal(0);
                $arCuentaCobrarAct->setVrSaldo(0);
                $arCuentaCobrarAct->setVrSaldoOperado(0);
                $arCuentaCobrarAct->setEstadoAnulado(1);
                $em->persist($arCuentaCobrarAct);
            }
        }
        return $respuesta;
    }

    public function crearReciboMasivoLista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $numeroReferencia =null;
        $fechaDesde =null;
        $fechaHasta =null;
        $estadoAutorizado =null;
        $estadoAprobado =null;
        $estadoAnulado =null;
        $cuentaCobrarTipo =null;
        if ($filtros) {
            $numeroReferencia = $filtros['numeroReferencia'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
            $cuentaCobrarTipo = $filtros['cuentaCobrarTipo'] ?? null;
        }
        $em = $this->getEntityManager();

        $queryBuilder = $em->createQueryBuilder()
            ->from('App:Cartera\CarCuentaCobrar', 'cc')
            ->addSelect("cc.codigoCuentaCobrarPk")
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.numeroReferencia')
            ->addSelect("cct.nombre as tipo")
            ->addSelect("c.nombreCorto")
            ->addSelect("cc.fecha")
            ->addSelect("cc.fechaVence")
            ->addSelect("cc.vrTotal")
            ->addSelect("cc.vrSaldo")
            ->leftJoin('cc.clienteRel', 'c')
            ->leftJoin('cc.cuentaCobrarTipoRel', 'cct')
            ->andWhere("cc.vrSaldo > 0")
            ->andWhere("cct.permiteReciboMasivo = 1");
        if ($cuentaCobrarTipo) {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk='{$cuentaCobrarTipo}'");
        }
        if ($numeroReferencia) {
            $queryBuilder->andWhere("cc.numeroReferencia = {$numeroReferencia}");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("cc.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("cc.fecha <= '{$fechaHasta} 23:59:59'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("cc.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cc.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("cc.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cc.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("cc.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("cc.estadoAnulado = 1");
                break;
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function corregirSaldos()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.vrSaldoOriginal')
            ->addSelect('cc.operacion');
        //->where('cc.codigoCuentaCobrarPk=531');
        $arCuentasCobrar = $queryBuilder->getQuery()->getResult();
        foreach ($arCuentasCobrar as $arCuentaCobrar) {

            //Afectar cuenta cobrar
            $abonos = 0;
            $queryBuilder = $em->createQueryBuilder()->from(CarReciboDetalle::class, 'rd')
                ->Select("SUM(rd.vrPagoAfectar) AS totalAfectar")
                ->leftJoin('rd.reciboRel', 'r')
                ->where("rd.codigoCuentaCobrarFk = " . $arCuentaCobrar['codigoCuentaCobrarPk'])
                ->andWhere("r.estadoAutorizado = 1")
                ->andWhere("r.estadoAnulado = 0");
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if ($arrResultado['totalAfectar']) {
                    $abonos += $arrResultado['totalAfectar'];
                }
            }

            //Afectar cuenta aplicacion
            //La diferencia es que la afectacion es del valor del pago no de pago_aplicar
            $queryBuilder = $em->createQueryBuilder()->from(CarReciboDetalle::class, 'rd')
                ->Select("SUM(rd.vrPago) AS totalAfectar")
                ->leftJoin('rd.reciboRel', 'r')
                ->where("rd.codigoCuentaCobrarAplicacionFk = " . $arCuentaCobrar['codigoCuentaCobrarPk'])
                ->andWhere("r.estadoAutorizado = 1")
                ->andWhere("r.estadoAnulado = 0");
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if ($arrResultado['totalAfectar']) {
                    $abonos += $arrResultado['totalAfectar'];
                }
            }

            $queryBuilder = $em->createQueryBuilder()->from(CarAplicacion::class, 'a')
                ->Select("SUM(a.vrAplicacion) as vrAplicacion")
                ->where("a.codigoCuentaCobrarFk = " . $arCuentaCobrar['codigoCuentaCobrarPk'])
                ->orWhere("a.codigoCuentaCobrarAplicacionFk = " . $arCuentaCobrar['codigoCuentaCobrarPk']);
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if ($arrResultado['vrAplicacion']) {
                    $abonos += $arrResultado['vrAplicacion'];
                }
            }

            $queryBuilder = $em->createQueryBuilder()->from(CarIngresoDetalle::class, 'id')
                ->Select("SUM(id.vrPago) as vrPago")
                ->where("id.codigoCuentaCobrarFk = " . $arCuentaCobrar['codigoCuentaCobrarPk']);
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if ($arrResultado['vrPago']) {
                    $abonos += $arrResultado['vrPago'];
                }
            }

            $saldo = $arCuentaCobrar['vrSaldoOriginal'] - $abonos;
            $saldoOperado = $saldo * $arCuentaCobrar['operacion'];
            $arCuentaCobrarAct = $em->getRepository(CarCuentaCobrar::class)->find($arCuentaCobrar['codigoCuentaCobrarPk']);
            $arCuentaCobrarAct->setVrSaldo($saldo);
            $arCuentaCobrarAct->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrarAct->setVrAbono($abonos);
            $em->persist($arCuentaCobrarAct);
        }
        $em->flush();
        return true;
    }

    public function ajustePesoCorreccion()
    {
        $em = $this->getEntityManager();
        $contador = 0;
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.vrSaldoOriginal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.operacion')
            ->where('cc.vrSaldo >= -1 AND cc.vrSaldo <= 1 AND cc.vrSaldo <>0');
        $arCuentasCobrar = $queryBuilder->getQuery()->getResult();
        foreach ($arCuentasCobrar as $arCuentaCobrar) {
            $abonos = $arCuentaCobrar['vrAbono'];
            $saldoOriginal = $arCuentaCobrar['vrSaldoOriginal'];
            $diferencia = $saldoOriginal - $abonos;
            if ($diferencia != 0) {
                if ($diferencia >= -5) {
                    if ($diferencia <= 5) {
                        $saldo = $arCuentaCobrar['vrSaldoOriginal'] - ($abonos + $diferencia);
                        if ($saldo == 0) {
                            $arCuentaCobrarAct = $em->getRepository(CarCuentaCobrar::class)->find($arCuentaCobrar['codigoCuentaCobrarPk']);
                            $arCuentaCobrarAct->setVrSaldo(0);
                            $arCuentaCobrarAct->setVrSaldoOperado(0);
                            $arCuentaCobrarAct->setVrAjustePesoSistema($diferencia);
                            $em->persist($arCuentaCobrarAct);
                            $contador++;
                        }
                    }
                }
            }
        }
        $em->flush();
        Mensajes::success("El proceso se ejecuto con exito corrigiendo automaticamente " . $contador . " registros");
        return true;
    }

    public function anular($arCuentaCobrar)
    {
        /**
         * @var $arCuentaCobrar CarCuentaCobrar
         */
        $em = $this->getEntityManager();
        $validacion = true;
        if ($arCuentaCobrar->getEstadoAprobado() == 1 && $arCuentaCobrar->getEstadoAnulado() == 0) {
            $arRecibosDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoCuentaCobrarFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
            foreach ($arRecibosDetalles as $arRecibosDetalleDetalle) {
                if ($arRecibosDetalleDetalle) {
                    Mensajes::error("No se puede anular la cuenta por cobrar porque esta asociada a un recibo detalle con código {$arRecibosDetalleDetalle->getCodigoReciboDetallePk()}");
                    $validacion = false;
                    break;
                }
            }
            $arNotasCreditoDetalles = $em->getRepository(CarNotaCreditoDetalle::class)->findBy(array('codigoCuentaCobrarFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
            foreach ($arNotasCreditoDetalles as $arNotasCreditoDetalle) {
                if ($arNotasCreditoDetalle) {
                    Mensajes::error("No se puede anular la cuenta por cobrar porque esta asociada a una nota credito detalle con código {$arNotasCreditoDetalle->getCodigoNotaCreditoDetallePk()}");
                    $validacion = false;
                    break;
                }
            }
            $arNotasDebitoDetalles = $em->getRepository(CarNotaDebitoDetalle::class)->findBy(array('codigoCuentaCobrarFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
            foreach ($arNotasDebitoDetalles as $arNotasDebitoDetalle) {
                if ($arNotasDebitoDetalle) {
                    Mensajes::error("No se puede anular la cuenta por cobrar porque esta asociada a una nota debito detalle con código {$arNotasDebitoDetalle->getCodigoNotaDebitoDetallePk()}");
                    $validacion = false;
                    break;
                }
            }
            $arCompromisosDetalles = $em->getRepository(CarCompromisoDetalle::class)->findBy(array('codigoCuentaCobrarFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
            foreach ($arCompromisosDetalles as $arCompromisosDetalle) {
                if ($arCompromisosDetalle) {
                    Mensajes::error("No se puede anular la cuenta por cobrar porque esta asociada a un compromiso detalle con código {$arCompromisosDetalle->getCodigoCompromisoDetallePk()}");
                    $validacion = false;
                    break;
                }
            }
            $arAplicaciones = $em->getRepository(CarAplicacion::class)->findBy(array('codigoCuentaCobrarAplicacionFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
            foreach ($arAplicaciones as $arAplicacion) {
                if ($arAplicacion) {
                    Mensajes::error("No se puede anular la cuenta por cobrar porque esta asociada a una aplicacion con código {$arAplicacion->getCodigoAplicacionPk()}");
                    $validacion = false;
                    break;
                }
            }
            if ($validacion == true) {
                $arCuentaCobrar->setEstadoAnulado(1);
                $arCuentaCobrar->setVrSubtotal(0);
                $arCuentaCobrar->setVrIva(0);
                $arCuentaCobrar->setVrRetencionFuente(0);
                $arCuentaCobrar->setVrRetencionIva(0);
                $arCuentaCobrar->setVrTotal(0);
                $arCuentaCobrar->setVrSaldo(0);
                $arCuentaCobrar->setVrSaldoOriginal(0);
                $arCuentaCobrar->setVrSaldoOperado(0);
                $em->persist($arCuentaCobrar);
                $em->flush();
            }
        }
    }

    public function cuentasCobrarDetalleNuevo($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.numeroReferencia')
            ->addSelect('cc.soporte')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldoOriginal')
            ->addSelect('cc.vrSaldoOperado')
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre as cuentaCobrarTipoNombre')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentificacion')
            ->join('cc.clienteRel', 'c')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');


        if ($session->get('filtroCarCuentaCobrarTodosClientes')) {
            if ($session->get('filtroCarCodigoCliente') != "") {
                $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
            }
        } else {
            $queryBuilder->andWhere("cc.codigoClienteFk = {$codigoCliente}");
        }

        if ($session->get('filtroCarCuentaCobrarNumero') != "") {
            $queryBuilder->andWhere("cc.numeroDocumento LIKE '%{$session->get('filtroCarCuentaCobrarNumero')}%'");
        }
        if ($session->get('filtroCarCuentaCobrarSoporte') != "") {
            $queryBuilder->andWhere("cc.soporte LIKE '%{$session->get('filtroCarCuentaCobrarSoporte')}%'");
        }
        if ($session->get('filtroCarCuentaCobrarCodigo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarPk = {$session->get('filtroCarCuentaCobrarCodigo')}");
        }
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '{$session->get('filtroCarCuentaCobrarTipo')}'");
        }

        if ($session->get('filtroCarFechaDesde') != null) {
            $queryBuilder->andWhere("cc.fecha >= '{$session->get('filtroCarFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroCarFechaHasta') != null) {
            $queryBuilder->andWhere("cc.fecha <= '{$session->get('filtroCarFechaHasta')} 23:59:59'");
        }


        return $queryBuilder;
    }

}