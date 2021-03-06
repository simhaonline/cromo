<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarAplicacion;
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
use App\Entity\General\GenResolucionFactura;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaDetalleConcepto;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Utilidades\FacturaElectronica;
use App\Utilidades\Mensajes;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
class TteFacturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
            ->addSelect('f.codigoFacturaClaseFk AS clase')
            ->addSelect('ft.nombre AS facturaTipo')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('f.plazoPago')
            ->addSelect('f.guias')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.codigoFacturaPk <> 0');
        if($session->get('TteFactura_numero') != ''){
            $queryBuilder->andWhere("f.numero = {$session->get('TteFactura_numero')}");
        }
        if($session->get('TteFactura_codigoFacturaPk') != ''){
            $queryBuilder->andWhere("f.codigoFacturaPk = {$session->get('TteFactura_codigoFacturaPk')}");
        }
        if ($session->get('TteFactura_codigoClienteFk')) {
            $queryBuilder->andWhere("f.codigoClienteFk = '{$session->get('TteFactura_codigoClienteFk')}'");
        }
        if ($session->get('TteFactura_codigoFacturaTipoFk')) {
            $queryBuilder->andWhere("f.codigoFacturaTipoFk = '{$session->get('TteFactura_codigoFacturaTipoFk')}'");
        }
        if ($session->get('TteFactura_codigoOperacionFk')) {
            $queryBuilder->andWhere("f.codigoOperacionFk = '{$session->get('TteFactura_codigoOperacionFk')}'");
        }
        if ($session->get('TteFactura_fechaDesde') != null) {
            $queryBuilder->andWhere("f.fecha >= '{$session->get('TteFactura_fechaDesde')} 00:00:00'");
        }

        if ($session->get('TteFactura_fechaHasta') != null) {
            $queryBuilder->andWhere("f.fecha <= '{$session->get('TteFactura_fechaHasta')} 23:59:59'");
        }
        switch ($session->get('TteFactura_estadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAutorizado = 1");
                break;
        }
        switch ($session->get('TteFactura_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAprobado = 1");
                break;
        }
        switch ($session->get('TteFactura_estadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAnulado = 1");
                break;
        }
        return $queryBuilder;
    }

    /*
     *evaluar si es necesaria a futuro
     * andres cano
     */
    public function listaProvicional($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoClienteFk= null;
        $numero = null;
        $codigoFacturaPk = null;
        $codigoFacturaTipoFk = null;
        $codigoOperacionFk = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAprobado = null;
        $estadoAutorizado = null;
        $estadoAnulado = null;

        if ($filtros){
            $codigoFacturaPk = $filtros['codigoFacturaPk']??null;
            $codigoClienteFk= $filtros['codigoClienteFk']??null;
            $numero = $filtros['numero']??null;
            $codigoFacturaTipoFk = $filtros['codigoFacturaTipoFk']??null;
            $codigoOperacionFk = $filtros['codigoOperacionFk']??null;
            $fechaDesde = $filtros['fechaDesde']??null;
            $fechaHasta = $filtros['fechaHasta']??null;
            $estadoAprobado = $filtros['estadoAprobado']??null;
            $estadoAutorizado = $filtros['estadoAutorizado']??null;
            $estadoAnulado = $filtros['estadoAnulado']??null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.codigoFacturaClaseFk AS clase')
            ->addSelect('ft.nombre AS facturaTipo')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('f.plazoPago')
            ->addSelect('f.guias')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.codigoFacturaPk <> 0');
        $queryBuilder->orderBy('f.codigoFacturaPk', 'DESC');
        if($numero){
            $queryBuilder->andWhere("f.numero = '{$numero}'");
        }
        if($codigoFacturaPk){
            $queryBuilder->andWhere("f.codigoFacturaPk = '{$codigoFacturaPk}'");
        }
        if ($codigoClienteFk) {
            $queryBuilder->andWhere("f.codigoClienteFk = '{$codigoClienteFk}'");
        }
        if ($codigoFacturaTipoFk) {
            $queryBuilder->andWhere("f.codigoFacturaTipoFk = '{$codigoFacturaTipoFk}'");
        }
        if ($codigoOperacionFk) {
            $queryBuilder->andWhere("f.codigoOperacionFk = '{$codigoOperacionFk}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("f.fecha >= '{$fechaDesde} 00:00:00'");
        }

        if ($fechaHasta) {
            $queryBuilder->andWhere("f.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAnulado = 1");
                break;
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function listaInforme()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('c.numeroIdentificacion AS nit')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('op.nombre AS operacion')
            ->addSelect('f.guias')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('f.codigoFacturaClaseFk')
            ->addSelect('ft.nombre AS facturaTipo')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->leftJoin('f.operacionRel', 'op')
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
        if ($session->get('filtroTteOperacion') != '') {
            $queryBuilder->andWhere("f.codigoOperacionFk =  '{$session->get('filtroTteOperacion')}'");
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
                    if($arFactura->getFacturaConceptoRel()->getLiberarGuias()) {
                        $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoFacturado = 0, g.estadoFacturaGenerada = 0, g.codigoFacturaFk = null, g.codigoFacturaPlanillaFk = null  
                        WHERE g.codigoFacturaFk = :codigoFactura')
                            ->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk());
                        $query->execute();
                    }
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
                $arCuentaCobrar->setCodigoCentroCostoFk($arFactura->getOperacionRel()->getCodigoCentroCostoFk());
                $arCuentaCobrar->setAsesorRel($arFactura->getClienteRel()->getAsesorRel());
                $em->persist($arCuentaCobrar);
                $em->flush();

                if($arFactura->getCodigoFacturaClaseFk() == 'NC') {
                    $em->getRepository(CarAplicacion::class)->aplicar($arCuentaCobrar, 'TTE', $arFactura->getCodigoFacturaReferenciaFk());
                }
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
            if($arFactura->getEstadoContabilizado() == 0) {
                if($arFactura->getEstadoAprobado() == 1) {
                    if($arFactura->getEstadoAnulado() == 0) {
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
                            if($arFactura->getCodigoFacturaClaseFk() == "FA") {
                                $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.codigoFacturaFk = null, g.codigoFacturaPlanillaFk = null, g.estadoFacturado = 0, g.estadoFacturaGenerada = 0, g.fechaFactura=NULL 
                                WHERE g.codigoFacturaFk = :codigoFactura')->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk());
                                $query->execute();
                            }


                            $arFactura->setVrTotal(0);
                            $arFactura->setVrTotalOperado(0);
                            $arFactura->setVrSubtotal(0);
                            $arFactura->setVrFlete(0);
                            $arFactura->setVrManejo(0);
                            $arFactura->setGuias(0);
                            $arFactura->setVrOtros(0);
                            $arFactura->setEstadoAnulado(1);
                            $arFactura->setVrRetencionFuente(0);
                            $arFactura->setVrTotalNeto(0);
                            $em->persist($arFactura);
                            $query = $em->createQuery('UPDATE App\Entity\Transporte\TteFacturaDetalle fd set fd.vrFlete = 0, fd.vrManejo = 0,  fd.unidades = 0, 
                            fd.pesoReal = 0, fd.pesoVolumen = 0, fd.vrDeclara = 0 
                            WHERE fd.codigoFacturaFk = :codigoFactura')->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk());
                            $query->execute();
                            $query = $em->createQuery('UPDATE App\Entity\Transporte\TteFacturaDetalleConcepto fdc set fdc.vrPrecio = 0, fdc.vrSubtotal = 0,  fdc.vrIva = 0, 
                                fdc.vrTotal = 0 
                                WHERE fdc.codigoFacturaFk = :codigoFactura')->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk());
                            $query->execute();
                            $em->flush();


                    } else {
                        Mensajes::error("La factura no puede estar previamente anulada");
                    }
                } else {
                    Mensajes::error("La factura debe estar aprobada");
                }
            } else {
                Mensajes::error("La factura ya esta contabilizada");
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
        if($session->get('filtroTteFacturaNumero') != ''){
            $queryBuilder->andWhere("f.numero = {$session->get('filtroTteFacturaNumero')}");
        }
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

    public function listaFacturaElectronica()
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
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentificacion')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('ft.nombre AS facturaTipo')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.estadoFacturaElectronica =  0')
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
            ->addSelect('f.fechaVence')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoContabilizado')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrTotal')
            ->addSelect('f.codigoOperacionFk')
            ->addSelect('ft.codigoCuentaClienteFk')
            ->addSelect('ft.codigoComprobanteFk')
            ->addSelect('ft.prefijo')
            ->addSelect('ft.contabilizarIngresoInicialFijo')
            ->addSelect('ft.codigoCuentaIngresoInicialFijoFleteFk')
            ->addSelect('ft.codigoCuentaIngresoInicialFijoManejoFk')
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
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arFactura['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(TteCliente::class)->terceroFinanciero($arFactura['codigoClienteFk']);

                        //Ingresos flete y manejo
                        if($arFactura['contabilizarIngresoInicialFijo']) {
                            $arOperacion = $em->getRepository(TteOperacion::class)->find($arFactura['codigoOperacionFk']);
                            $arCentroCosto = null;
                            if($arFactura['codigoCentroCostoFk']) {
                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
                            }
                            //Cuenta del ingreso flete
                            if($arFactura['codigoCuentaIngresoInicialFijoFleteFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaIngresoInicialFijoFleteFk']);
                                if(!$arCuenta) {
                                    $error = "No se encuentra la cuenta del flete " . $arFactura['codigoCuentaIngresoInicialFijoFleteFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                $arRegistro->setNumero($arFactura['numero']);
                                $arRegistro->setFecha($arFactura['fecha']);
                                if($arFactura['codigoFacturaClaseFk'] == 'FA') {
                                    $arRegistro->setVrCredito($arFactura['vrFlete']);
                                    $arRegistro->setNaturaleza('C');
                                } else {
                                    $arRegistro->setVrDebito($arFactura['vrFlete']);
                                    $arRegistro->setNaturaleza('D');
                                }
                                if($arCuenta->getExigeDocumentoReferencia()) {
                                    if($arFactura['codigoFacturaClaseFk'] == "FA") {
                                        $arRegistro->setNumeroReferencia($arFactura['numero']);
                                        $arRegistro->setNumeroReferenciaPrefijo($arFactura['prefijo']);
                                    }
                                }
                                $arRegistro->setDescripcion('INGRESO FLETE CLIENTE');
                                $arRegistro->setCodigoModeloFk('TteFactura');
                                $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "En el tipo de factura no esta configurada configurada una cuenta de ingreso para flete fijo en el documento numero " . $arFactura['numero'];
                                break;
                            }

                            //Cuenta del ingreso manejo
                            if($arFactura['codigoCuentaIngresoInicialFijoManejoFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaIngresoInicialFijoManejoFk']);
                                if(!$arCuenta) {
                                    $error = "No se encuentra la cuenta del manejo " . $arFactura['codigoCuentaIngresoInicialFijoManejoFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                $arRegistro->setNumero($arFactura['numero']);
                                $arRegistro->setFecha($arFactura['fecha']);
                                if($arFactura['codigoFacturaClaseFk'] == 'FA') {
                                    $arRegistro->setVrCredito($arFactura['vrManejo']);
                                    $arRegistro->setNaturaleza('C');
                                } else {
                                    $arRegistro->setVrDebito($arFactura['vrManejo']);
                                    $arRegistro->setNaturaleza('D');
                                }
                                if($arCuenta->getExigeDocumentoReferencia()) {
                                    if($arFactura['codigoFacturaClaseFk'] == "FA") {
                                        $arRegistro->setNumeroReferencia($arFactura['numero']);
                                        $arRegistro->setNumeroReferenciaPrefijo($arFactura['prefijo']);
                                    }
                                }
                                $arRegistro->setDescripcion('INGRESO MANEJO CLIENTE');
                                $arRegistro->setCodigoModeloFk('TteFactura');
                                $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "En el tipo de factura no esta configurada configurada una cuenta de ingreso para manejo fijo en el documento numero " . $arFactura['numero'];
                                break;
                            }
                        } else {
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
                                    $arRegistro->setFecha($arFactura['fecha']);
                                    if($arFactura['codigoFacturaClaseFk'] == 'FA') {
                                        $arRegistro->setVrCredito($arIngreso['vrFlete']);
                                        $arRegistro->setNaturaleza('C');
                                    } else {
                                        $arRegistro->setVrDebito($arIngreso['vrFlete']);
                                        $arRegistro->setNaturaleza('D');
                                    }
                                    if($arCuenta->getExigeDocumentoReferencia()) {
                                        if($arFactura['codigoFacturaClaseFk'] == "FA") {
                                            $arRegistro->setNumeroReferencia($arFactura['numero']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arFactura['prefijo']);
                                        }
                                    }
                                    $arRegistro->setDescripcion('INGRESO FLETE CLIENTE');
                                    $arRegistro->setCodigoModeloFk('TteFactura');
                                    $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "La operacion no tiene configurada una cuenta de ingreso para flete " . " en el documento numero " . $arFactura['numero'];
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
                                    $arRegistro->setFecha($arFactura['fecha']);
                                    if($arFactura['codigoFacturaClaseFk'] == 'FA') {
                                        $arRegistro->setVrCredito($arIngreso['vrManejo']);
                                        $arRegistro->setNaturaleza('C');
                                    } else {
                                        $arRegistro->setVrDebito($arIngreso['vrManejo']);
                                        $arRegistro->setNaturaleza('D');
                                    }
                                    if($arCuenta->getExigeDocumentoReferencia()) {
                                        if($arFactura['codigoFacturaClaseFk'] == "FA") {
                                            $arRegistro->setNumeroReferencia($arFactura['numero']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arFactura['prefijo']);
                                        }
                                    }
                                    $arRegistro->setDescripcion('INGRESO MANEJO CLIENTE');
                                    $arRegistro->setCodigoModeloFk('TteFactura');
                                    $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "La operacion no tiene configurada una cuenta de ingreso para manejo" . " en el documento numero " . $arFactura['numero'];
                                    break;
                                }
                            }
                        }


                        //Otros ingresos
                        $arFacturaDetalleConceptos = $em->getRepository(TteFacturaDetalleConcepto::class)->findBy(array('codigoFacturaFk' => $arFactura['codigoFacturaPk']));
                        if($arFacturaDetalleConceptos) {
                            $arCentroCosto = null;
                            if($arFactura['codigoCentroCostoFk']) {
                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
                            }
                            foreach ($arFacturaDetalleConceptos as $arFacturaDetalleConcepto) {
                                if($arFacturaDetalleConcepto->getFacturaConceptoDetalleRel()->getCodigoCuentaFk()) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arFacturaDetalleConcepto->getFacturaConceptoDetalleRel()->getCodigoCuentaFk());
                                    if(!$arCuenta) {
                                        $error = "No se encuentra la cuenta " . $arFacturaDetalleConcepto->getFacturaConceptoDetalleRel()->getCodigoCuentaFk();
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                    $arRegistro->setNumero($arFactura['numero']);
                                    $arRegistro->setFecha($arFactura['fecha']);
                                    if($arFactura['codigoFacturaClaseFk'] == 'FA') {
                                        $arRegistro->setVrCredito($arFacturaDetalleConcepto->getVrSubtotal());
                                        $arRegistro->setNaturaleza('C');
                                    } else {
                                        $arRegistro->setVrDebito($arFacturaDetalleConcepto->getVrSubtotal());
                                        $arRegistro->setNaturaleza('D');
                                    }
                                    $arRegistro->setDescripcion($arFacturaDetalleConcepto->getFacturaConceptoDetalleRel()->getNombre());
                                    $arRegistro->setCodigoModeloFk('TteFactura');
                                    $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El concepto no tiene configurada la cuenta ";
                                    break;
                                }
                            }
                        }

                        //Cuenta cliente
                        if($arFactura['codigoCuentaClienteFk']) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaClienteFk']);
                            if(!$arCuenta) {
                                $error = "No se encuentra la cuenta cliente " . $arFactura['codigoCuentaClienteFk'] . " en el documento numero " . $arFactura['numero'];
                                break;
                            }
                            $prefijoReferencia = "";
                            $numeroReferencia = "";
                            $arComprobanteReferencia = null;
                            if($arFactura['codigoFacturaClaseFk'] == "FA") {
                                $prefijoReferencia = $arFactura['prefijo'];
                                $numeroReferencia = $arFactura['numero'];
                                $arComprobanteReferencia = $arComprobante;
                            }
                            if($arFactura['codigoFacturaClaseFk'] == "NC") {
                                if($arFactura['codigoFacturaReferenciaFk']) {
                                    $arFacturaReferencia = $em->getRepository(TteFactura::class)->find($arFactura['codigoFacturaReferenciaFk']);
                                    if($arFacturaReferencia) {
                                        $prefijoReferencia = $arFacturaReferencia->getFacturaTipoRel()->getPrefijo();
                                        $numeroReferencia = $arFacturaReferencia->getNumero();
                                        $arComprobanteReferencia = $em->getRepository(FinComprobante::class)->find($arFacturaReferencia->getFacturaTipoRel()->getCodigoComprobanteFk());
                                    } else {
                                        $prefijoReferencia = $arFactura['prefijo'];
                                        $numeroReferencia = $arFactura['numero'];
                                        $arComprobanteReferencia = $arComprobante;
                                    }
                                } else {
                                    $prefijoReferencia = $arFactura['prefijo'];
                                    $numeroReferencia = $arFactura['numero'];
                                    $arComprobanteReferencia = $arComprobante;
                                }
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
                            $arRegistro->setNumeroReferencia($numeroReferencia);
                            $arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                            $arRegistro->setComprobanteReferenciaRel($arComprobanteReferencia);
                            $arRegistro->setFecha($arFactura['fecha']);
                            $arRegistro->setFechaVence($arFactura['fechaVence']);
                            if($arFactura['codigoFacturaClaseFk'] == 'FA') {
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
            ->select("SUM(f.vrFlete) as flete")
            ->leftJoin("f.facturaTipoRel", "ft")
            ->where("f.fecha >='" . $fechaDesde . "' AND f.fecha <= '" . $fechaHasta . "'")
            ->andWhere('ft.intermediacion = 1')
            ->andWhere('f.estadoAprobado = 1');
        $arrResultado = $queryBuilder->getQuery()->getSingleResult();
        if($arrResultado['flete']) {
            $valor = $arrResultado['flete'];
        }
        return $valor;
    }

    public function fleteCobroTotal($fechaDesde, $fechaHasta)
    {
        $valor = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select("SUM(f.vrFlete*f.operacionComercial) as flete")
            ->leftJoin("f.facturaTipoRel", "ft")
            ->where("f.fecha >='" . $fechaDesde . "' AND f.fecha <= '" . $fechaHasta . "'")
            ->andWhere('ft.intermediacion = 1')
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
            ->leftJoin("f.facturaTipoRel", "ft")
            ->where("f.fecha >='" . $fechaDesde . "' AND f.fecha <= '" . $fechaHasta . "'")
            ->andWhere("ft.intermediacion = 1")
            ->andWhere('f.estadoAprobado = 1')
            ->groupBy('f.codigoClienteFk')
            ->addGroupBy('f.codigoFacturaTipoFk');
        $arrResultado = $queryBuilder->getQuery()->getResult();

        return $arrResultado;
    }

    public function facturaElectronica($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $arrConfiguracion = $em->getRepository(GenConfiguracion::class)->facturaElectronica();
            foreach ($arr AS $codigo) {
                /** @var $arFactura TteFactura*/
                $arFactura = $em->getRepository(TteFactura::class)->find($codigo);
                if($arFactura->getEstadoAprobado() && !$arFactura->getEstadoFacturaElectronica()) {
                    if($arFactura->getCodigoResolucionFacturaFk()) {
                        /** @var $arResolucionFactura GenResolucionFactura */
                        $arResolucionFactura = $em->getRepository(GenResolucionFactura::class)->find($arFactura->getCodigoResolucionFacturaFk());
                        /** @var $arCliente TteCliente */
                        $arCliente = $arFactura->getClienteRel();
                        if($arResolucionFactura) {
                            $arrFactura = [
                                'dat_nitFacturador' => $arrConfiguracion['nit'],
                                'dat_claveTecnica' => $arrConfiguracion['feToken'],
                                'dat_claveTecnicaCadena' => 'fc8eac422eba16e22ffd8c6f94b3f40a6e38162c',
                                'dat_tipoAmbiente' => '2',
                                'res_numero' => $arResolucionFactura->getNumero(),
                                'res_prefijo' => $arResolucionFactura->getPrefijo(),
                                'res_fechaDesde' => $arResolucionFactura->getFechaDesde()->format('Y-m-d'),
                                'res_fechaHasta' => $arResolucionFactura->getFechaHasta()->format('Y-m-d'),
                                'res_desde' => $arResolucionFactura->getNumeroDesde(),
                                'res_hasta' => $arResolucionFactura->getNumeroHasta(),
                                'doc_numero' => $arFactura->getNumero(),
                                'doc_fecha' => $arFactura->getFecha()->format('Y-m-d'),
                                'doc_hora' => '12:00:00-05:00',
                                'doc_subtotal' => number_format($arFactura->getVrSubtotal(), 2, '.', ''),
                                'doc_iva' => number_format($arFactura->getVrIva(), 2, '.', ''),
                                'doc_inc' => number_format(0, 2, '.', ''),
                                'doc_ica' => number_format(0, 2, '.', ''),
                                'doc_total' => number_format($arFactura->getVrTotal(), 2, '.', ''),
                                'em_tipoPersona' => $arrConfiguracion['codigoTipoPersonaFk'],
                                'em_numeroIdentificacion' => $arrConfiguracion['nit'],
                                'em_nombreCompleto' => $arrConfiguracion['nombre'],
                                'ad_tipoIdentificacion' => $arCliente->getIdentificacionRel()->getCodigoEntidad(),
                                'ad_numeroIdentificacion' => $arCliente->getNumeroIdentificacion(),
                                'ad_digitoVerificacion' => $arCliente->getDigitoVerificacion(),
                                'ad_nombreCompleto' => $arCliente->getNombreExtendido(),
                                'ad_tipoPersona' => $arCliente->getTipoPersonaRel()->getCodigoInterface(),
                                'ad_regimen' => $arCliente->getRegimenRel()->getCodigoInterface(),
                                'ad_responsabilidadFiscal' => $arCliente->getResponsabilidadFiscalRel()->getCodigoInterface(),
                                'ad_direccion' => $arCliente->getDireccion(),
                                'ad_barrio' => $arCliente->getBarrio(),
                                'ad_codigoPostal' => $arCliente->getCodigoPostal(),
                                'ad_telefono' => $arCliente->getTelefono(),
                                'ad_codigoCIUU' => $arCliente->getCodigoCIUU(),
                            ];
                            $facturaElectronica = new FacturaElectronica($em);
                            //$facturaElectronica->enviarDispapeles($arrFactura);
                            $facturaElectronica->enviarCadena($arrFactura);
                        } else {
                            Mensajes::error("La resolucion de la factura no existe");
                            break;
                        }
                    } else {
                        Mensajes::error("La factura no tiene una resolucion asignada");
                        break;
                    }
                } else {
                    Mensajes::error("El documento debe estar aprobado y sin enviar a facturacion electronica");
                    break;
                }
            }
        }
        return true;
    }

}
