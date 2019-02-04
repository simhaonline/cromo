<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenImpuesto;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaDetalleConcepto;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Utilidades\Mensajes;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class TteFacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFactura::class);
    }

    public function listaDql(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT f.codigoFacturaPk, 
        f.numero,
        f.fecha,
        f.vrFlete,
        f.vrManejo,
        f.vrSubtotal,
        f.vrTotal,        
        c.nombreCorto clienteNombre       
        FROM App\Entity\Transporte\TteFactura f
        LEFT JOIN f.clienteRel c'
        );
        return $query->execute();
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('f.guias')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('f.codigoFacturaClaseFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('ft.nombre AS facturaTipo')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.codigoFacturaPk <> 0');
        $fecha =  new \DateTime('now');
        if($session->get('filtroTteFacturaNumero') != ''){
            $queryBuilder->andWhere("f.numero = {$session->get('filtroTteFacturaNumero')}");
        }
        if($session->get('filtroTteFacturaCodigo') != ''){
            $queryBuilder->andWhere("f.codigoFacturaPk = {$session->get('filtroTteFacturaCodigo')}");
        }
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("f.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if($session->get('filtroFecha') == true){
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("f.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("f.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("f.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        if($session->get('filtroTteFacturaCodigoFacturaTipo')) {
            $queryBuilder->andWhere("f.codigoFacturaTipoFk = '" . $session->get('filtroTteFacturaCodigoFacturaTipo') . "'");
        }
        switch ($session->get('filtroTteFacturaEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAprobado = 1");
                break;
        }
        switch ($session->get('filtroTteFacturaEstadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAnulado = 1");
                break;
        }
        $queryBuilder->orderBy('f.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('f.fecha', 'DESC');
        $queryBuilder->addOrderBy('f.codigoFacturaPk', 'DESC');

        return $queryBuilder->getQuery()->execute();
    }

    public function listaInforme()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('f.guias')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('f.codigoFacturaClaseFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('ft.nombre AS facturaTipo')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.codigoFacturaPk <> 0');
        $fecha =  new \DateTime('now');
        if($session->get('filtroTteFacturaNumero') != ''){
            $queryBuilder->andWhere("f.numero = {$session->get('filtroTteFacturaNumero')}");
        }
        if($session->get('filtroTteFacturaCodigo') != ''){
            $queryBuilder->andWhere("f.codigoFacturaPk = {$session->get('filtroTteFacturaCodigo')}");
        }
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("f.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if($session->get('filtroFecha') == true){
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("f.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("f.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("f.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        switch ($session->get('filtroTteFacturaEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAprobado = 1");
                break;
        }
        switch ($session->get('filtroTteFacturaEstadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAnulado = 1");
                break;
        }
        if($session->get('filtroTteFacturaCodigoFacturaTipo')) {
            $queryBuilder->andWhere("f.codigoFacturaTipoFk = '" . $session->get('filtroTteFacturaCodigoFacturaTipo') . "'");
        }
        $queryBuilder->orderBy('f.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('f.fecha', 'DESC');
        $queryBuilder->addOrderBy('f.codigoFacturaPk', 'DESC');

        return $queryBuilder;
    }

    public function controlFactura()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('c.nombreCorto')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotalOperado')
            ->addSelect('f.operacionComercial')
            ->addSelect('ft.nombre')
            ->addSelect('f.codigoFacturaClaseFk')
            ->addSelect('ft.nombre')
            ->addSelect('f.codigoFacturaTipoFk')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.codigoFacturaPk <> 0')
            ->andWhere('f.estadoAprobado = 1')
            ->andWhere('f.estadoAnulado = 0')
            ->orderBy('f.codigoFacturaTipoFk, f.numero','DESC');
        $fecha =  new \DateTime('now');
        if ($session->get('filtroTteFecha') != null) {
            $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroTteFecha')} 00:00:00' AND f.fecha <= '{$session->get('filtroTteFecha')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("f.fecha ='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }

        return $queryBuilder->getQuery()->execute();
    }

    public function liquidar($id): bool
    {
        $em = $this->getEntityManager();
        $arrImpuestoRetenciones = array();
        $arFactura = $em->getRepository(TteFactura::class)->find($id);
        $subTotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $ivaGeneral = 0;
        $fleteGeneral = 0;
        $manejoGeneral = 0;
        $cantidadGeneral = 0;
        $retencionFuenteGlobal = 0;

        $arFacturaDetalles = $this->getEntityManager()->getRepository(TteFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $subTotal =  $arFacturaDetalle->getVrFlete() + $arFacturaDetalle->getVrManejo();
            $subTotalGeneral += $subTotal;
            $fleteGeneral += $arFacturaDetalle->getVrFlete();
            $manejoGeneral += $arFacturaDetalle->getVrManejo();
            $cantidadGeneral++;
            $totalGeneral += $subTotal;
            if($arFacturaDetalle->getCodigoImpuestoRetencionFk()) {
                if (!array_key_exists($arFacturaDetalle->getCodigoImpuestoRetencionFk(), $arrImpuestoRetenciones)) {
                    $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()] =  array('codigo' => $arFacturaDetalle->getCodigoImpuestoRetencionFk(),
                        'valor' => $subTotal);
                } else {
                    $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['valor'] += $subTotal;
                }
            }
            //$this->getEntityManager()->persist($arMovimientoDetalle);
        }

        $arFacturaDetalleConceptos = $this->getEntityManager()->getRepository(TteFacturaDetalleConcepto::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]);
        foreach ($arFacturaDetalleConceptos as $arFacturaDetalleConcepto) {
            $subTotal = $arFacturaDetalleConcepto->getVrSubtotal();
            $subTotalGeneral += $subTotal;
            $ivaGeneral += $arFacturaDetalleConcepto->getVrIva();
            $total = $arFacturaDetalleConcepto->getVrTotal();
            $totalGeneral += $total;
            //$subTotalGeneral += $subTotal;
        }
        //Retencion en la fuente
        if($arrImpuestoRetenciones) {
            foreach ($arrImpuestoRetenciones as $arrImpuestoRetencion) {
                $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrImpuestoRetencion['codigo']);
                if($arImpuesto) {
                    if($arrImpuestoRetencion['valor'] >= $arImpuesto->getBase() || $arFactura->getClienteRel()->getRetencionFuenteSinBase()) {
                        $retencionFuenteGlobal += ($arrImpuestoRetencion['valor'] * $arImpuesto->getPorcentaje()) / 100;
                    }
                }
            }
        }


        $arFactura->setGuias($cantidadGeneral);
        $arFactura->setVrFlete($fleteGeneral);
        $arFactura->setVrManejo($manejoGeneral);
        $arFactura->setVrSubtotal($subTotalGeneral);
        $arFactura->setVrIva($ivaGeneral);
        $arFactura->setVrTotal($totalGeneral);
        $arFactura->setVrTotalOperado($totalGeneral * $arFactura->getOperacionComercial());
        $arFactura->setVrRetencionFuente($retencionFuenteGlobal);
        $totalNeto = $totalGeneral - $retencionFuenteGlobal;
        $arFactura->setVrTotalNeto($totalNeto);
        $em->persist($arFactura);

        //Facturas planillas
        $arFacturaPlanillas = $em->getRepository(TteFacturaPlanilla::class)->findBy(array('codigoFacturaFk' => $id));
        foreach ($arFacturaPlanillas as $arFacturaPlanilla) {
            $query = $em->createQuery(
                'SELECT COUNT(fd.codigoFacturaDetallePk) as cantidad, SUM(fd.unidades+0) as unidades, SUM(fd.pesoReal+0) as pesoReal,
            SUM(fd.pesoVolumen+0) as pesoVolumen, SUM(fd.vrFlete+0) as vrFlete, SUM(fd.vrManejo+0) as vrManejo
            FROM App\Entity\Transporte\TteFacturaDetalle fd
            WHERE fd.codigoFacturaFk = :codigoFactura AND fd.codigoFacturaPlanillaFk = :codigoFacturaPlanilla')
                ->setParameter('codigoFactura', $id)
            ->setParameter('codigoFacturaPlanilla', $arFacturaPlanilla->getCodigoFacturaPlanillaPk());

            $arrGuias = $query->getSingleResult();
            $vrSubtotal = intval($arrGuias['vrFlete']) + intval($arrGuias['vrManejo']);
            $arFacturaPlanillaAct = $em->getRepository(TteFacturaPlanilla::class)->find($arFacturaPlanilla->getCodigoFacturaPlanillaPk());
            $arFacturaPlanillaAct->setGuias(intval($arrGuias['cantidad']));
            $arFacturaPlanillaAct->setVrFlete(intval($arrGuias['vrFlete']));
            $arFacturaPlanillaAct->setVrManejo(intval($arrGuias['vrManejo']));
            $arFacturaPlanillaAct->setVrTotal($vrSubtotal);
            $em->persist($arFacturaPlanillaAct);
        }


        $em->flush();
        return true;
    }

    /**
     * @param $arrDetalles
     * @param $arFactura TteFactura
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function retirarDetalle($arrDetalles, $arFactura): bool
    {
        $em = $this->getEntityManager();
        if ($arrDetalles) {
            if (count($arrDetalles) > 0) {
                foreach ($arrDetalles AS $codigo) {
                    $arFacturaDetalle = $em->getRepository(TteFacturaDetalle::class)->find($codigo);
                    if($arFacturaDetalle->getCodigoFacturaPlanillaFk() == "") {
                        if($arFactura->getCodigoFacturaClaseFk() == 'FA') {
                            $arGuia = $em->getRepository(TteGuia::class)->find($arFacturaDetalle->getCodigoGuiaFk());
                            $arGuia->setFacturaRel(NULL);
                            $arGuia->setEstadoFacturaGenerada(0);
                            $em->persist($arGuia);
                        }
                        $em->remove($arFacturaDetalle);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    public function retirarDetallePlanilla($arrDetalles, $arFactura): bool
    {
        $em = $this->getEntityManager();
        if ($arrDetalles) {
            if (count($arrDetalles) > 0) {
                foreach ($arrDetalles AS $codigo) {
                    $arFacturaDetalle = $em->getRepository(TteFacturaDetalle::class)->find($codigo);
                    if($arFacturaDetalle->getCodigoFacturaPlanillaFk() != "") {
                        if($arFactura->getCodigoFacturaClaseFk() == 'FA') {
                            $arGuia = $em->getRepository(TteGuia::class)->find($arFacturaDetalle->getCodigoGuiaFk());
                            $arGuia->setFacturaRel(NULL);
                            $arGuia->setEstadoFacturaGenerada(0);
                            $em->persist($arGuia);
                        }
                        $em->remove($arFacturaDetalle);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $arrDetalles
     * @param $arFactura TteFactura
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function retirarConcepto($arrDetalles, $arFactura): bool
    {
        $em = $this->getEntityManager();
        if ($arrDetalles) {
            if (count($arrDetalles) > 0) {
                foreach ($arrDetalles AS $codigo) {
                    $arFacturaDetalleConcepto = $em->getRepository(TteFacturaDetalleConcepto::class)->find($codigo);
                    $em->remove($arFacturaDetalleConcepto);
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $arFactura TteFactura
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arFactura)
    {
        $cantDetalles = count($this->getEntityManager()->getRepository(TteFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]));
        $cantDetallesConcepto = count($this->getEntityManager()->getRepository(TteFacturaDetalleConcepto::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]));
        if ($cantDetalles > 0  || $cantDetallesConcepto > 0) {
            $arFactura->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arFactura);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arFactura TteFactura
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desAutorizar($arFactura)
    {
        if ($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAprobado() == 0) {
            $arFactura->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arFactura);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    /**
     * @param $arFactura TteFactura
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arFactura): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        $objFunciones = new FuncionesController();
        if (!$arFactura->getEstadoAprobado()) {
            if ($arFactura->getGuias() > 0 || $arFactura->getVrSubtotal() > 0) {
                $fechaActual = new \DateTime('now');
                if($arFactura->getCodigoFacturaClaseFk() == 'FA') {
                    $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoFacturado = 1, g.fechaFactura=:fecha 
                      WHERE g.codigoFacturaFk = :codigoFactura')
                        ->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk())
                        ->setParameter('fecha', $fechaActual->format('Y-m-d H:i'));
                    $query->execute();
                }
                if($arFactura->getCodigoFacturaClaseFk() == 'NC') {
                    $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoFacturado = 0, g.estadoFacturaGenerada = 0, g.codigoFacturaFk = null, g.codigoFacturaPlanillaFk = null  
                      WHERE g.codigoFacturaFk = :codigoFactura')
                        ->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk());
                    $query->execute();
                }
                $arFactura->setEstadoAprobado(1);
                $fecha = new \DateTime('now');
                $arFactura->setFecha($fecha);
                $arFactura->setFechaVence($objFunciones->sumarDiasFechaNumero($arFactura->getPlazoPago(), $fecha));
                $arFacturaTipo = $this->getEntityManager()->getRepository(TteFacturaTipo::class)->find($arFactura->getCodigoFacturaTipoFk());
                if($arFactura->getNumero() == null) {
                    $arFactura->setNumero($arFacturaTipo->getConsecutivo());
                    $arFacturaTipo->setConsecutivo($arFacturaTipo->getConsecutivo() + 1);
                }
                $this->getEntityManager()->persist($arFactura);
                $this->getEntityManager()->persist($arFacturaTipo);

                $arClienteCartera = $em->getRepository(CarCliente::class)->findOneBy(['codigoIdentificacionFk' => $arFactura->getClienteRel()->getCodigoIdentificacionFk(),'numeroIdentificacion' => $arFactura->getClienteRel()->getNumeroIdentificacion()]);
                if (!$arClienteCartera) {
                    $arClienteCartera = new CarCliente();
                    $arClienteCartera->setFormaPagoRel($arFactura->getClienteRel()->getFormaPagoRel());
                    $arClienteCartera->setIdentificacionRel($arFactura->getClienteRel()->getIdentificacionRel());
                    $arClienteCartera->setNumeroIdentificacion($arFactura->getClienteRel()->getNumeroIdentificacion());
                    $arClienteCartera->setDigitoVerificacion($arFactura->getClienteRel()->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arFactura->getClienteRel()->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arFactura->getClienteRel()->getPlazoPago());
                    $arClienteCartera->setDireccion($arFactura->getClienteRel()->getDireccion());
                    $arClienteCartera->setTelefono($arFactura->getClienteRel()->getTelefono());
                    $arClienteCartera->setCorreo($arFactura->getClienteRel()->getCorreo());
                    $em->persist($arClienteCartera);
                }

                $arCuentaCobrarTipo = $em->getRepository(CarCuentaCobrarTipo::class)->find($arFactura->getFacturaTipoRel()->getCodigoCuentaCobrarTipoFk());
                $arCuentaCobrar = new CarCuentaCobrar();
                $arCuentaCobrar->setClienteRel($arClienteCartera);
                $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                $arCuentaCobrar->setFecha($arFactura->getFecha());
                $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                $arCuentaCobrar->setModulo("TTE");
                $arCuentaCobrar->setCodigoDocumento($arFactura->getCodigoFacturaPk());
                $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                $arCuentaCobrar->setVrSubtotal($arFactura->getVrSubtotal());
                $arCuentaCobrar->setVrTotal($arFactura->getVrTotal());
                $arCuentaCobrar->setVrSaldoOriginal($arFactura->getVrTotal());
                $arCuentaCobrar->setVrRetencionFuente($arFactura->getVrRetencionFuente());
                $arCuentaCobrar->setVrSaldo($arFactura->getVrTotal());
                $arCuentaCobrar->setVrSaldoOperado($arFactura->getVrTotal() * $arCuentaCobrarTipo->getOperacion());
                $arCuentaCobrar->setPlazo($arFactura->getPlazoPago());
                $arCuentaCobrar->setOperacion($arCuentaCobrarTipo->getOperacion());
                $em->persist($arCuentaCobrar);
                $em->flush();
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->contabilidadAutomatica();
                if ($arConfiguracion['contabilidadAutomatica']) {
                    $this->contabilizar(array($arFactura->getCodigoFacturaPk()));
                }
            } else {
                $respuesta = "La factura debe tener guias asignadas";
            }
        } else {
            $respuesta = "La factura no puede estar previamente aprobada";
        }

        return $respuesta;
    }

    public function anular($arFactura): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        if($arFactura->getCodigoFacturaClaseFk() == "FA") {
            if($arFactura->getEstadoContabilizado() == 0) {
                if($arFactura->getEstadoAprobado() == 1) {
                    if($arFactura->getEstadoAnulado() == 0) {
                        if($arFactura->getCodigoFacturaClaseFk() == 'FA') {
                            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->findOneBy(array('modulo' => 'TTE', 'codigoDocumento' => $arFactura->getCodigoFacturaPk()));
                            if($arCuentaCobrar) {
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
                            $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.codigoFacturaFk = null, g.codigoFacturaPlanillaFk = null, g.estadoFacturado = 0, g.estadoFacturaGenerada = 0, g.fechaFactura=NULL 
                                WHERE g.codigoFacturaFk = :codigoFactura')->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk());
                            $query->execute();

                            $arFactura->setVrTotal(0);
                            $arFactura->setVrSubtotal(0);
                            $arFactura->setVrFlete(0);
                            $arFactura->setVrManejo(0);
                            $arFactura->setGuias(0);
                            $arFactura->setVrOtros(0);
                            $arFactura->setEstadoAnulado(1);
                            $em->persist($arFactura);
                            $query = $em->createQuery('UPDATE App\Entity\Transporte\TteFacturaDetalle fd set fd.vrFlete = 0, fd.vrManejo = 0,  fd.unidades = 0, 
                            fd.pesoReal = 0, fd.pesoVolumen = 0, fd.vrDeclara = 0 
                            WHERE fd.codigoFacturaFk = :codigoFactura')->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk());
                            $query->execute();
                            $em->flush();

                        }
                    } else {
                        Mensajes::error("La factura no puede estar previamente anulada");
                    }
                } else {
                    Mensajes::error("La factura debe estar aprobada");
                }
            } else {
                Mensajes::error("La factura ya esta contabilizada");
            }
        } else {
            Mensajes::error("Solo se pueden anular las facturas");
        }
        return $respuesta;
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteFactura::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arRegistro->getCodigoFacturaPk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    public function notaCredito($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('f.guias')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('f.codigoFacturaClaseFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('ft.nombre AS facturaTipo')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.codigoClienteFk = ' . $codigoCliente)
        ->andWhere('f.estadoAprobado = 1')
            ->andWhere('f.estadoAnulado = 0');
        $queryBuilder->orderBy('f.fecha', 'DESC');
        return $queryBuilder;
    }

    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('f.guias')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('f.codigoFacturaClaseFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('ft.nombre AS facturaTipo')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.estadoContabilizado =  0')
        ->andWhere('f.estadoAprobado = 1');
        $fecha =  new \DateTime('now');
        if($session->get('filtroTteFacturaNumero') != ''){
            $queryBuilder->andWhere("f.numero = {$session->get('filtroTteFacturaNumero')}");
        }
        if($session->get('filtroTteFacturaCodigo') != ''){
            $queryBuilder->andWhere("f.codigoFacturaPk = {$session->get('filtroTteFacturaCodigo')}");
        }
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("f.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if($session->get('filtroFecha') == true){
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("f.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("f.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("f.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        if($session->get('filtroTteFacturaCodigoFacturaTipo')) {
            $queryBuilder->andWhere("f.codigoFacturaTipoFk = '" . $session->get('filtroTteFacturaCodigoFacturaTipo') . "'");
        }

        return $queryBuilder->getQuery()->execute();
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.codigoClienteFk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoContabilizado')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrTotal')
            ->addSelect('ft.naturalezaCuentaCliente')
            ->addSelect('ft.codigoCuentaClienteFk')
            ->addSelect('ft.codigoComprobanteFk')
            ->addSelect('ft.prefijo')
            ->addSelect('o.codigoCentroCostoFk')
            ->addSelect('f.codigoFacturaReferenciaFk')
            ->addSelect('f.codigoFacturaClaseFk')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->leftJoin('f.operacionRel', 'o')
            ->where('f.codigoFacturaPk = ' . $codigo);
        $arFactura = $queryBuilder->getQuery()->getSingleResult();
        return $arFactura;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            foreach ($arr AS $codigo) {
                $arFactura = $em->getRepository(TteFactura::class)->registroContabilizar($codigo);
                if($arFactura) {
                    if($arFactura['estadoAprobado'] == 1 && $arFactura['estadoContabilizado'] == 0) {
                        $prefijoReferencia = "";
                        $numeroReferencia = "";
                        if($arFactura['codigoFacturaClaseFk'] == "FA") {
                            $prefijoReferencia = $arFactura['prefijo'];
                            $numeroReferencia = $arFactura['numero'];
                        }
                        if($arFactura['codigoFacturaClaseFk'] == "NC") {
                            $arFacturaReferencia = $em->getRepository(TteFactura::class)->find($arFactura['codigoFacturaReferenciaFk']);
                            if($arFacturaReferencia) {
                                $prefijoReferencia = $arFacturaReferencia->getFacturaTipoRel()->getPrefijo();
                                $numeroReferencia = $arFacturaReferencia->getNumero();
                            } else {
                                $prefijoReferencia = $arFactura['prefijo'];
                                $numeroReferencia = $arFactura['numero'];
                            }
                        }
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arFactura['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(TteCliente::class)->terceroFinanciero($arFactura['codigoClienteFk']);

                        $arIngresos = $em->getRepository(TteFacturaDetalle::class)->contabilizarIngreso($arFactura['codigoFacturaPk']);
                        foreach ($arIngresos as $arIngreso) {
                            $arOperacion = $em->getRepository(TteOperacion::class)->find($arIngreso['codigoOperacionIngresoFk']);
                            $arCentroCosto = null;
                            if($arOperacion->getCodigoCentroCostoFk()) {
                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arOperacion->getCodigoCentroCostoFk());
                            }

                            //Cuenta del ingreso flete
                            if($arOperacion->getCodigoCuentaIngresoFleteFk()) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arOperacion->getCodigoCuentaIngresoFleteFk());
                                if(!$arCuenta) {
                                    $error = "No se encuentra la cuenta del flete " . $arOperacion->getCodigoCuentaIngresoFleteFk();
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                $arRegistro->setNumero($arFactura['numero']);
                                $arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                                $arRegistro->setNumeroReferencia($numeroReferencia);
                                $arRegistro->setFecha($arFactura['fecha']);
                                $arRegistro->setVrCredito($arIngreso['vrFlete']);
                                $arRegistro->setNaturaleza('C');
                                $arRegistro->setDescripcion('INGRESO FLETE CLIENTE');
                                $arRegistro->setCodigoModeloFk('TteFactura');
                                $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "La operacion no tiene configurada una cuenta de ingreso para flete";
                                break;
                            }

                            //Cuenta del ingreso manejo
                            if($arOperacion->getCodigoCuentaIngresoManejoFk()) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arOperacion->getCodigoCuentaIngresoManejoFk());
                                if(!$arCuenta) {
                                    $error = "No se encuentra la cuenta del manejo " . $arOperacion->getCodigoCuentaIngresoManejoFk();
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                $arRegistro->setNumero($arFactura['numero']);
                                $arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                                $arRegistro->setNumeroReferencia($numeroReferencia);
                                $arRegistro->setFecha($arFactura['fecha']);
                                $arRegistro->setVrCredito($arIngreso['vrManejo']);
                                $arRegistro->setNaturaleza('C');
                                $arRegistro->setDescripcion('INGRESO MANEJO CLIENTE');
                                $arRegistro->setCodigoModeloFk('TteFactura');
                                $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "La operacion no tiene configurada una cuenta de ingreso para manejo";
                                break;
                            }
                        }



                        //Cuenta cliente
                        if($arFactura['codigoCuentaClienteFk']) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaClienteFk']);
                            if(!$arCuenta) {
                                $error = "No se encuentra la cuenta cliente " . $arFactura['codigoCuentaClienteFk'];
                                break;
                            }
                            $arRegistro = new FinRegistro();
                            $arRegistro->setTerceroRel($arTercero);
                            $arRegistro->setCuentaRel($arCuenta);
                            $arRegistro->setComprobanteRel($arComprobante);
                            if($arCuenta->getExigeCentroCosto()) {
                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                            }
                            $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                            $arRegistro->setNumero($arFactura['numero']);
                            $arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                            $arRegistro->setNumeroReferencia($numeroReferencia);
                            $arRegistro->setFecha($arFactura['fecha']);
                            if($arFactura['naturalezaCuentaCliente'] == 'D') {
                                $arRegistro->setVrDebito($arFactura['vrTotal']);
                                $arRegistro->setNaturaleza('D');
                            } else {
                                $arRegistro->setVrCredito($arFactura['vrTotal']);
                                $arRegistro->setNaturaleza('C');
                            }
                            $arRegistro->setDescripcion('CLIENTES');
                            $arRegistro->setCodigoModeloFk('TteFactura');
                            $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                            $em->persist($arRegistro);
                        } else {
                            $error = "El tipo de factura no tiene configurada la cuenta cliente";
                            break;
                        }
                        $arFacturaAct = $em->getRepository(TteFactura::class)->find($arFactura['codigoFacturaPk']);
                        $arFacturaAct->setEstadoContabilizado(1);
                        $em->persist($arFacturaAct);
                    }
                } else {
                    $error = "La factura codigo " . $codigo . " no existe";
                    break;
                }
            }
            if($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }

        }
        return true;
    }

    public function fleteCobro($fechaDesde, $fechaHasta)
    {
        $valor = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select("SUM(f.vrFlete * f.operacionComercial) as flete")
            ->where("f.fecha >='" . $fechaDesde . "' AND f.fecha <= '" . $fechaHasta . "'")
        ->andWhere('f.estadoAprobado = 1');
        $arrResultado = $queryBuilder->getQuery()->getSingleResult();
        if($arrResultado['flete']) {
            $valor = $arrResultado['flete'];
        }
        return $valor;
    }

    public function fleteCobroDetallado($fechaDesde, $fechaHasta)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select("f.codigoClienteFk")
            ->addSelect('f.codigoFacturaTipoFk')
            ->addSelect("SUM(f.vrFlete) as flete")
            ->where("f.fecha >='" . $fechaDesde . "' AND f.fecha <= '" . $fechaHasta . "'")
        ->andWhere('f.estadoAprobado = 1')
        ->groupBy('f.codigoClienteFk')
        ->addGroupBy('f.codigoFacturaTipoFk');
        $arrResultado = $queryBuilder->getQuery()->getResult();

        return $arrResultado;
    }

}