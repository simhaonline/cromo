<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarReciboDetalle;
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

    public function lista()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->leftJoin('cc.clienteRel','cl')
            ->leftJoin('cc.cuentaCobrarTipoRel','cct')
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
            ->where('cc.codigoCuentaCobrarPk <> 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'DESC');
        $fecha =  new \DateTime('now');
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo')."'");
        }
        if($session->get('filtroCarNumeroReferencia') != ''){
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if($session->get('filtroCarCuentaCobrarNumero') != ''){
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroCarCuentaCobrarTipo')) {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo') . "'");
        }
        if($session->get('filtroFecha') == true){
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

    /**
     * @param $arrSeleccionados
     */
    public function eliminar($arrSeleccionados){

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
            ->leftJoin('cc.clienteRel','cl')
            ->leftJoin('cc.cuentaCobrarTipoRel','cct')
            ->where('cc.codigoCuentaCobrarPk <> 0')
            ->andWhere('cc.vrSaldo > 0')
            ->orderBy('cl.nombreCorto', 'ASC')
            ->addOrderBy('cc.fecha', 'ASC');
        $fecha =  new \DateTime('now');
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo')."'");
        }
        if($session->get('filtroCarNumeroReferencia') != ''){
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if($session->get('filtroCarCuentaCobrarNumero') != ''){
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroCarCuentaCobrarTipo')) {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo') . "'");
        }
        if($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("cc.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        if($session->get('filtroFecha') == true){
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
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre')
            ->join('cc.clienteRel','cl')
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
            ->join('cc.clienteRel','cl')
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
            ->join('cc.clienteRel','cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo > 0')
            ->andWhere('cc.operacion = -1')
            ->andWhere('cc.codigoClienteFk = ' . $codigoCliente)
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
            ->join('cc.clienteRel','cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo > 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    public function generarVencimientos () {
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
            if($dias <= 0) {
                $rango = 1;
            } else {
                if($dias <= 30 ) {
                    $rango = 2;
                } else {
                    if($dias <= 60 ) {
                        $rango = 3;
                    } else {
                        if($dias <= 90 ) {
                            $rango = 4;
                        } else {
                            if($dias <= 180 ) {
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
            ->join('cc.clienteRel','c')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->orderBy('c.nombreCorto', 'ASC')
            ->addOrderBy('cc.rango', 'DESC')
            ->addOrderBy('cc.fecha', 'ASC')
        ->addOrderBy('cc.numeroDocumento', 'ASC');

        $fecha =  new \DateTime('now');
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo')."'");
        }
        if($session->get('filtroCarNumeroReferencia') != ''){
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if($session->get('filtroCarCuentaCobrarNumero') != ''){
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if($session->get('filtroFecha') == true){
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
            ->join('cc.clienteRel','c')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->orderBy('c.nombreCorto', 'ASC')
            ->addOrderBy('cc.fecha', 'ASC');

        $fecha =  new \DateTime('now');
        if ($session->get('filtroCarCuentaCobrarTipo') != "") {
            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo')."'");
        }
        if($session->get('filtroCarNumeroReferencia') != ''){
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if($session->get('filtroCarCuentaCobrarNumero') != ''){
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if($session->get('filtroFecha') == true){
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
        if($arCuentaCobrar) {
            $arRecibosDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoCuentaCobrarFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
            if($arRecibosDetalles) {
                Mensajes::error("La cuenta por cobrar enlazada a este documento tiene recibos de caja, no se puede anular el documento");
                $respuesta = false;
            } else {
                $arCuentaCobrarAct = $em->getRepository(CarCuentaCobrar::class)->find($arCuentaCobrar->getCodigoCuentaCobrarPk());
                $arCuentaCobrarAct->setVrSubtotal(0);
                $arCuentaCobrarAct->setVrTotal(0);
                $arCuentaCobrarAct->setVrIva(0);
                $arCuentaCobrarAct->setVrRetencionFuente(0);
                $arCuentaCobrarAct->setVrRetencionIva(0);
                $arCuentaCobrarAct->setVrSaldo(0);
                $arCuentaCobrarAct->setVrSaldoOperado(0);
                $arCuentaCobrarAct->setEstadoAnulado(1);
                $em->persist($arCuentaCobrarAct);
            }
        }
        return $respuesta;
    }

    public function crearReciboMasivoLista(){
        $em=$this->getEntityManager();
        $session=new Session();

        $arCrearReciboMasivo=$em->createQueryBuilder()
            ->from('App:Cartera\CarCuentaCobrar','cc')
            ->addSelect("cc.codigoCuentaCobrarPk")
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.numeroReferencia')
            ->addSelect("cct.nombre as tipo")
            ->addSelect("c.nombreCorto")
            ->addSelect("cc.fecha")
            ->addSelect("cc.fechaVence")
            ->addSelect("cc.vrTotal")
            ->addSelect("cc.vrSaldo")
            ->leftJoin('cc.clienteRel','c')
            ->leftJoin('cc.cuentaCobrarTipoRel','cct')
            ->andWhere("cc.vrSaldo > 0")
            ->andWhere("cct.permiteReciboMasivo = 1");
        if($session->get('filtroCarReciboCodigoReciboTipo')){
            $arCrearReciboMasivo->andWhere("cc.codigoCuentaCobrarTipoFk='{$session->get("filtroCarReciboCodigoReciboTipo")}'");
        }
        if($session->get('filtroCarNumeroReferencia') != ''){
            $arCrearReciboMasivo->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        return $arCrearReciboMasivo->getQuery()->execute();
    }

    public function corregirSaldos()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.vrSaldoOriginal')
            ->addSelect('cc.operacion');
        //->where('cc.codigoCuentaCobrarPk=2205');
        $arCuentasCobrar = $queryBuilder->getQuery()->getResult();
        foreach ($arCuentasCobrar as $arCuentaCobrar) {

            $abonos = 0;
            $queryBuilder = $em->createQueryBuilder()->from(CarReciboDetalle::class, 'rd')
                ->Select("SUM(rd.vrPagoAfectar) AS totalAfectar")
                ->where("rd.codigoCuentaCobrarFk = " . $arCuentaCobrar['codigoCuentaCobrarPk'])
            ->orWhere("rd.codigoCuentaCobrarAplicacionTipoFk = " . $arCuentaCobrar['codigoCuentaCobrarPk']);
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if($arrResultado['totalAfectar']) {
                    $abonos += $arrResultado['totalAfectar'];
                }
            }

            $queryBuilder = $em->createQueryBuilder()->from(CarAplicacion::class, 'a')
                ->Select("SUM(a.vrAplicacion) as vrAplicacion")
                ->where("a.codigoCuentaCobrarFk = " . $arCuentaCobrar['codigoCuentaCobrarPk'])
            ->orWhere("a.codigoCuentaCobrarAplicacionFk = " . $arCuentaCobrar['codigoCuentaCobrarPk']);
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if($arrResultado['vrAplicacion']) {
                    $abonos += $arrResultado['vrAplicacion'];
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
            if($diferencia != 0) {
                if($diferencia >= -5 ){
                    if($diferencia <= 5) {
                        $saldo = $arCuentaCobrar['vrSaldoOriginal'] - ($abonos + $diferencia);
                        if($saldo == 0) {
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

}