<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuPagoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuPagoDetalle::class);
    }

    /**
     * @param $codigoPago
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($codigoPago)
    {
        if ($codigoPago) {
            $query = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
                ->leftJoin('pd.conceptoRel', 'c')
                ->select('pd.codigoPagoDetallePk')
                ->addSelect('pd.codigoConceptoFk')
                ->addSelect('ABS(pd.codigoConceptoFk) AS HIDDEN concepto')
                ->addSelect('c.nombre')
                ->addSelect('pd.detalle')
                ->addSelect('pd.porcentaje')
                ->addSelect('pd.horas')
                ->addSelect('pd.dias')
                ->addSelect('pd.vrHora')
                ->addSelect('pd.operacion')
                ->addSelect('pd.vrPago')
                ->addSelect('pd.vrDevengado')
                ->addSelect('pd.vrDeduccion')
                ->addSelect('pd.vrPagoOperado')
                ->addSelect('pd.vrIngresoBasePrestacion')
                ->addSelect('pd.vrIngresoBaseCotizacion')
                ->where("pd.codigoPagoFk = {$codigoPago}")
                ->orderBy('concepto');
        } else {
            $query = null;
        }
        return $query ? $query->getQuery()->execute() : null;
    }

    public function pagosDetallesProgramacionPago($codigoProgramacion)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT pd FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p "
            . "WHERE p.codigoProgramacionFk = " . $codigoProgramacion . " ORDER BY p.codigoEmpleadoFk, pd.codigoConceptoFk";
        $query = $em->createQuery($dql);
        $arPagosDetalles = $query->getResult();
        return $arPagosDetalles;
    }

    /**
     * @param $codigoPago
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function creditos($codigoProgramacion)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('pd.codigoPagoDetallePk')
            ->addSelect('pd.codigoCreditoFk')
            ->addSelect('pd.vrPago')
            ->leftJoin('pd.pagoRel', 'p')
            ->where('pd.codigoCreditoFk IS NOT NULL')
            ->andWhere('p.codigoProgramacionFk = ' . $codigoProgramacion);
        return $query->getQuery()->execute();
    }

    public function ibcMes($anio, $mes, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $ultimoDiaMes = date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
        $fechaDesde = $anio . "/" . $mes . "/" . "01";
        $fechaHasta = $anio . "/" . $mes . "/" . $ultimoDiaMes;
        $arrIbc = array('ibc' => 0, 'horas' => 0, 'deduccionAnterior' => 0);
        $query = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('SUM(pd.vrIngresoBaseCotizacion) as ibc')
            ->addSelect('SUM(pd.horas) as horas')
            ->leftJoin('pd.pagoRel', 'p')
            ->where("p.estadoAprobado = 1")
            ->andWhere('p.codigoContratoFk = ' . $codigoContrato)
            ->andWhere("p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaHasta <= '" . $fechaHasta . "'");
        $arrayResultado = $query->getQuery()->getSingleResult();
        if ($arrayResultado) {
            if ($arrayResultado['ibc']) {
                $arrIbc['ibc'] = $arrayResultado['ibc'];
            }
            if ($arrayResultado['horas']) {
                $arrIbc['horas'] = $arrayResultado['horas'];
            }
        }
        $query = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('SUM(pd.vrPago) as deduccionFondo')
            ->leftJoin('pd.pagoRel', 'p')
            ->leftJoin('pd.conceptoRel', 'c')
            ->where("p.estadoAprobado = 1")
            ->andWhere('p.codigoContratoFk = ' . $codigoContrato)
            ->andWhere("p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaHasta <= '" . $fechaHasta . "'")
            ->andWhere("c.fondoSolidaridadPensional = 1");
        $arrayResultado = $query->getQuery()->getSingleResult();
        if ($arrayResultado) {
            if ($arrayResultado['deduccionFondo']) {
                $arrIbc['deduccionAnterior'] = $arrayResultado['deduccionFondo'];
            }
        }
        return $arrIbc;
    }

    public function ibcOrdinario($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $arrIbc = array('ibc' => 0, 'horas' => 0);
        $query = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('SUM(pd.vrIngresoBaseCotizacion) as ibc')
            ->addSelect('SUM(pd.horas) as horas')
            ->leftJoin('pd.pagoRel', 'p')
            ->where("p.fechaDesdeContrato >= '" . $fechaDesde . "' AND p.fechaDesdeContrato <= '" . $fechaHasta . "' AND pd.codigoNovedadFk IS NULL AND pd.codigoVacacionFk IS NULL")
            ->andWhere('p.codigoContratoFk=' . $codigoContrato);
        $arrayResultado = $query->getQuery()->getResult();
        if ($arrayResultado) {
            $arrIbc['ibc'] = $arrayResultado[0]['ibc'];
            $arrIbc['horas'] = $arrayResultado[0]['horas'];
        }

        return $arrIbc;
    }

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->addSelect('pd.codigoPagoDetallePk')
            ->addSelect('pt.nombre as pagoTipoNombre')
            ->addSelect('p.numero as pagoNumero')
            ->addSelect('p.codigoContratoFk')
            ->addSelect('p.codigoEmpleadoFk as pagoCodigoEmpleadoFk')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('g.nombre as grupoNombre')
            ->addSelect('pd.codigoConceptoFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('p.fechaDesde as pagoFechaDesde')
            ->addSelect('p.fechaHasta as pagoFechaHasta')
            ->addSelect('pd.vrPagoOperado')
            ->addSelect('pd.vrPago')
            ->addSelect('pd.horas')
            ->addSelect('pd.dias')
            ->addSelect('pd.porcentaje')
            ->addSelect('pd.vrIngresoBaseCotizacion')
            ->addSelect('pd.vrIngresoBasePrestacion')
            ->addSelect('pd.codigoCreditoFk')
            ->addSelect('c.pension')
            ->addSelect('c.salud')
            ->addSelect('c.porcentajeVacaciones')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'p')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.pagoTipoRel', 'pt')
            ->leftJoin('p.grupoRel', 'g')
            ->orderBy('p.fechaDesde', 'DESC')
            ->setMaxResults(10000);
        if ($session->get('filtroRhuInformePagoDetalleTipo') != null) {
            $queryBuilder->andWhere("p.codigoPagoTipoFk = '{$session->get('filtroRhuInformePagoDetalleTipo')}'");
        }
        if ($session->get('filtroRhuInformePagoDetalleConcepto') != null) {
            $queryBuilder->andWhere("pd.codigoConceptoFk = '{$session->get('filtroRhuInformePagoDetalleConcepto')}'");
        }
        if ($session->get('filtroRhuInformePagoDetalleCodigoEmpleado') != null) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = {$session->get('filtroRhuInformePagoDetalleCodigoEmpleado')}");
        }
        if ($session->get('filtroRhuInformePagoDetalleCodigoContrato') != null) {
            $queryBuilder->andWhere("p.codigoContratoFk = {$session->get('filtroRhuInformePagoDetalleCodigoContrato')}");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("p.fechaDesde >= '{$session->get('filtroRhuInformePagoDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("p.fechaHasta <= '{$session->get('filtroRhuInformePagoDetalleFechaHasta')} 23:59:59'");
        }

        return $queryBuilder;

    }

    public function recargosNocturnosIbp($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select("SUM(pd.vrIngresoBasePrestacion  * (pc.porcentajeVacaciones / 100)) as recargoNocturno")
            ->from(RhuPagoDetalle::class, 'pd')
            ->join('pd.pagoRel', 'p')
            ->join('pd.conceptoRel', 'pc')
            ->where("pc.recargoNocturno = 1")
            ->andWhere("p.codigoContratoFk = {$codigoContrato}")
            ->andWhere("p.fechaDesdeContrato >= '{$fechaDesde}'")
            ->andWhere("p.fechaHastaContrato <= '{$fechaHasta}'");


        $arrayResultado = $query->getQuery()->getResult();
        $recargosNocturnos = $arrayResultado[0]['recargoNocturno'];
        return $recargosNocturnos;

    }

    public function listaRecargosNocturnosIbp($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select("p.numero as numero,pd.vrIngresoBasePrestacion as vrIngresoPrestacion,  (pc.porcentajeVacaciones / 100) as porcentaje,
             p.fechaDesde, p.fechaHasta, pc.nombre as concepto")
            ->from(RhuPagoDetalle::class, 'pd')
            ->join('pd.pagoRel', 'p')
            ->join('pd.conceptoRel', 'pc')
            ->where("pc.recargoNocturno = 1")
            ->andWhere("p.codigoContratoFk = {$codigoContrato}")
            ->andWhere("p.fechaDesde >= '{$fechaDesde}'")
            ->andWhere("p.fechaHasta <= '{$fechaHasta}'");


        $arrayResultado = $query->getQuery()->getResult();
        $recargosNocturnos = $arrayResultado;
        return $recargosNocturnos;

    }

    public function ibp($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(pd.vrIngresoBasePrestacion) as ibp FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p "
            . "WHERE p.estadoAprobado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesdeContrato >= '" . $fechaDesde . "' AND p.fechaDesdeContrato <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibp = $arrayResultado[0]['ibp'];
        if ($ibp == null) {
            $ibp = 0;
        }
        return $ibp;
    }

    public function ibpConceptos($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(pd.vrIngresoBasePrestacion) as ibp FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.conceptoRel pc "
            . "WHERE pc.comision = 1 AND p.estadoAprobado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesdeContrato >= '" . $fechaDesde . "' AND p.fechaDesdeContrato <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibp = $arrayResultado[0]['ibp'];
        if ($ibp == null) {
            $ibp = 0;
        }
        return $ibp;
    }

    public function recargosNocturnosFecha($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();

        $fechaDesdeAnio = $fechaHasta->format('Y-m-d');
        $nuevafecha = strtotime('-365 day', strtotime($fechaDesdeAnio));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        $fechaDesdeAnio = date_create($nuevafecha);
        if ($fechaDesde < $fechaDesdeAnio) {
            $fechaDesde = $fechaDesdeAnio;
            $meses = 12;
        } else {
            $interval = date_diff($fechaDesde, $fechaHasta);
            $meses = $interval->format('%m');
            if ($meses == 0) {
                $meses = 1;
            }
        }

        $dql = "SELECT SUM(pd.vrIngresoBaseCotizacion) as recagosNocturnos FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.conceptoRel pc "
            . "WHERE pc.recargoNocturno = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesde >= '" . $fechaDesde->format('Y/m/d') . "' AND p.fechaDesde <= '" . $fechaHasta->format('Y/m/d') . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $recargosNocturnos = $arrayResultado[0]['recagosNocturnos'];
        if ($recargosNocturnos == null) {
            $recargosNocturnos = 0;
        }
        $recargosNocturnos = $recargosNocturnos / $meses;
        //$recargosNocturnos =  $recargosNocturnos + $arContrato->getPromedioRecargoNocturnoInicial();
        return $recargosNocturnos;
    }

    public function ibpSuplementario($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(pd.vrSuplementario) as suplementario FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p "
            . "WHERE p.estadoAprobado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $suplementario = $arrayResultado[0]['suplementario'];
        if ($suplementario == null) {
            $suplementario = 0;
        }
        return $suplementario;
    }

    public function listaDql($codigoPago = "", $codigoProgramacionPagoDetalle = "")
    {
        $em = $this->getEntityManager();
        $dql = "SELECT pd FROM App\Entity\RecursoHumano\RhuPagoDetalle pd WHERE pd.codigoPagoDetallePk <> 0";

        if ($codigoPago != "") {
            $dql .= " AND pd.codigoPagoFk = " . $codigoPago;
        }
        if ($codigoProgramacionPagoDetalle != "") {
            $dql .= " AND pd.codigoProgramacionDetalleFk = " . $codigoProgramacionPagoDetalle;
        }
        $dql .= " ORDER BY pd.codigoConceptoFk";
        return $dql;
    }

    public function excelResumenEmpleado()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('p.codigoEmpleadoFk as COD')
            ->addSelect('e.nombreCorto as EMPLEADO')
            ->addSelect('SUM(pd.vrPago) AS valor')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'p')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.pagoTipoRel', 'pt')
            ->leftJoin('p.grupoRel', 'g')
            ->groupBy('p.codigoEmpleadoFk')
            ->setMaxResults(10000);

        if ($session->get('filtroRhuInformePagoDetalleCodigoEmpleado') != null) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = {$session->get('filtroRhuInformePagoDetalleCodigoEmpleado')}");
        }
        if ($session->get('filtroRhuInformePagoDetalleTipo') != null) {
            $queryBuilder->andWhere("p.codigoPagoTipoFk = '{$session->get('filtroRhuInformePagoDetalleTipo')}'");
        }
        if ($session->get('filtroRhuInformePagoDetalleConcepto') != null) {
            $queryBuilder->andWhere("pd.codigoConceptoFk = '{$session->get('filtroRhuInformePagoDetalleConcepto')}'");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("p.fechaDesde >= '{$session->get('filtroRhuInformePagoDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("p.fechaHasta <= '{$session->get('filtroRhuInformePagoDetalleFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

    public function excelResumenConcepto()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('pd.codigoConceptoFk as COD')
            ->addSelect('c.nombre as CONCEPTO')
            ->addSelect('SUM(pd.vrPago) AS valor')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'p')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.pagoTipoRel', 'pt')
            ->leftJoin('p.grupoRel', 'g')
            ->groupBy('pd.codigoConceptoFk')
            ->setMaxResults(10000);

        if ($session->get('filtroRhuInformePagoDetalleCodigoEmpleado') != null) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = {$session->get('filtroRhuInformePagoDetalleCodigoEmpleado')}");
        }
        if ($session->get('filtroRhuInformePagoDetalleTipo') != null) {
            $queryBuilder->andWhere("p.codigoPagoTipoFk = '{$session->get('filtroRhuInformePagoDetalleTipo')}'");
        }
        if ($session->get('filtroRhuInformePagoDetalleConcepto') != null) {
            $queryBuilder->andWhere("pd.codigoConceptoFk = '{$session->get('filtroRhuInformePagoDetalleConcepto')}'");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("p.fechaDesde >= '{$session->get('filtroRhuInformePagoDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("p.fechaHasta <= '{$session->get('filtroRhuInformePagoDetalleFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

    public function adicionalPrestacional($codigoPago, $codigoContrato = "")
    {
        $em = $this->getEntityManager();
        $arContrato = $em->getRepository("App\Entity\RecursoHumano\RhuContrato")->find($codigoContrato);
        $qb = $em->createQueryBuilder()->from("App\Entity\RecursoHumano\RhuPagoDetalle", "pd")
            ->join("pd.pagoRel", "p")
            ->leftJoin("pd.conceptoRel", "c")
            ->select("SUM(pd.vrPago) AS Pago")
            ->where("c.adicional = 1")
            ->andWhere("c.generaIngresoBasePrestacion = 1");
        if ($codigoContrato != "") {
            if ($arContrato->getFechaUltimoPagoVacaciones()) {
                $qb->andWhere("p.fechaDesde > '{$arContrato->getFechaUltimoPagoVacaciones()->format('Y-m-d')}'");
            }
        }
        if ($codigoPago != "") {
            $qb->andWhere("pd.codigoPagoFk = {$codigoPago}");
        }
        if ($codigoContrato != "") {
            $qb->andWhere("p.codigoContratoFk = {$codigoContrato}");
        }
        $arrayResultado = $qb->getQuery()->getResult();
        $adicionalPrestacional = $arrayResultado[0]['Pago'];
        if ($adicionalPrestacional == null) {
            $adicionalPrestacional = 0;
        }
        return $adicionalPrestacional;
    }

    public function valorLicenciaPago($codigoPago, $codigoContrato = "")
    {
        $em = $this->getEntityManager();
        $arContrato = $em->getRepository("App\Entity\RecursoHumano\RhuContrato")->find($codigoContrato);


        $qb = $em->createQueryBuilder()->from("App\Entity\RecursoHumano\RhuPagoDetalle", "pd")
            ->select("SUM(pd.vrIngresoBasePrestacion) AS pago")
            ->join("pd.pagoRel", "p")
            ->where("pd.codigoLicenciaFk IS NOT NULL");
        if ($codigoContrato != "") {
            if ($arContrato->getFechaUltimoPagoVacaciones()) {
                $qb->andWhere("p.fechaDesde > '{$arContrato->getFechaUltimoPagoVacaciones()->format('Y-m-d')}'");
            }
        }
        if ($codigoPago != "") {
            $qb->andWhere("pd.codigoPagoFk = {$codigoPago}");
        }
        if ($codigoContrato != "") {
            $qb->andWhere("p.codigoContratoFk = {$codigoContrato}");
        }

//        $dql = "SELECT SUM(pd.vrIngresoBasePrestacion) as pago FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd "
//            . "WHERE pd.codigoPagoFk = " . $codigoPago . " AND pd.codigoLicenciaFk IS NOT NULL";
//        $query = $em->createQuery($dql);
        $arrayResultado = $qb->getQuery()->getResult();
        $pago = $arrayResultado[0]['pago'];
        if ($pago == null) {
            $pago = 0;
        }
        return $pago;
    }

    public function valorIncapacidadPago($codigoPago, $codigoContrato = "")
    {
        $em = $this->getEntityManager();
        $arContrato = $em->getRepository("App\Entity\RecursoHumano\RhuContrato")->find($codigoContrato);

        $qb = $em->createQueryBuilder()->from("App\Entity\RecursoHumano\RhuPagoDetalle", "pd")
            ->select("SUM(pd.vrIngresoBasePrestacion) AS pago")
            ->join("pd.pagoRel", "p")
            ->where("pd.codigoIncapacidadFk IS NOT NULL");
        if ($codigoContrato != "") {
            if ($arContrato->getFechaUltimoPagoVacaciones()) {
                $qb->andWhere("p.fechaDesde > '{$arContrato->getFechaUltimoPagoVacaciones()->format('Y-m-d')}'");
            }
        }

        if ($codigoPago != "") {
            $qb->andWhere("pd.codigoPagoFk = {$codigoPago}");
        }
        if ($codigoContrato != "") {
            $qb->andWhere("p.codigoContratoFk = {$codigoContrato}");
        }
//        $dql = "SELECT SUM(pd.vrIngresoBasePrestacion) as pago FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd "
//            . "WHERE pd.codigoPagoFk = " . $codigoPago . " AND pd.codigoIncapacidadFk IS NOT NULL";
//        $query = $em->createQuery($dql);
        $arrayResultado = $qb->getQuery()->getResult();
        $pago = $arrayResultado[0]['pago'];
        if ($pago == null) {
            $pago = 0;
        }
        return $pago;
    }

    public function valorSalarioPago($codigoPago, $codigoContrato = "")
    {
        $em = $this->getEntityManager();
        $arContrato = $em->getRepository("App\Entity\RecursoHumano\RhuContrato")->find($codigoContrato);

        $qb = $em->createQueryBuilder()->from("App\Entity\RecursoHumano\RhuPagoDetalle", "pd")
            ->select("SUM(pd.vrPagoOperado) AS pago")
            ->join("pd.pagoRel", "p")
            ->where("pd.codigoConceptoFk = 1");
        if ($codigoContrato != "") {
            if ($arContrato->getFechaUltimoPagoVacaciones()) {
                $qb->andWhere("p.fechaDesde > '{$arContrato->getFechaUltimoPagoVacaciones()->format('Y-m-d')}'");
            }
        }

        if ($codigoPago != "") {
            $qb->andWhere("pd.codigoPagoFk = {$codigoPago}");
        }
        if ($codigoContrato != "") {
            $qb->andWhere("p.codigoContratoFk = {$codigoContrato}");
        }

//        $dql = "SELECT SUM(pd.vrPagoOperado) as pago FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd "
//            . "WHERE pd.codigoPagoFk = " . $codigoPago . " AND pd.codigoPagoConceptoFk = 1";
//        $query = $em->createQuery($dql);
        $arrayResultado = $qb->getQuery()->getResult();
        $pago = $arrayResultado[0]['pago'];
        if ($pago == null) {
            $pago = 0;
        }
        return $pago;
    }

    public function PagoDetalleIntercambio($codigoPago)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('pd.codigoPagoDetallePk')
            ->addSelect('pd.codigoPagoFk')
            ->addSelect('pd.codigoConceptoFk')
            ->addSelect('c.nombre as ConceptoNombre')
            ->addSelect('pd.codigoCreditoFk')
            ->addSelect('pd.codigoVacacionFk')
            ->addSelect('pd.codigoLicenciaFk')
            ->addSelect('pd.codigoNovedadFk')
            ->addSelect('pd.codigoIncapacidadFk')
            ->addSelect('pd.detalle')
            ->addSelect('pd.porcentaje')
            ->addSelect('pd.horas')
            ->addSelect('pd.dias')
            ->addSelect('pd.operacion')
            ->addSelect('pd.vrHora')
            ->addSelect('pd.vrPago')
            ->addSelect('pd.vrDevengado')
            ->addSelect('pd.vrDeduccion')
            ->addSelect('pd.vrPagoOperado')
            ->addSelect('pd.vrIngresoBasePrestacion')
            ->addSelect('pd.vrIngresoBaseCotizacion')
            ->addSelect('pd.vrIngresoBaseCotizacionAdicional')
            ->leftJoin('pd.conceptoRel', 'c')
            ->where("pd.codigoPagoFk = {$codigoPago}");

       return $queryBuilder->getQuery()->getArrayResult();

    }

    public function descuentoSalud($codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('SUM(pd.vrPago) AS valor')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'p')
        ->where("p.codigoContratoFk = {$codigoContrato}")
        ->andWhere("c.salud = 1")
        ->andWhere("p.fechaDesdeContrato >='{$fechaDesde}'")
        ->andWhere("p.fechaDesdeContrato <='{$fechaHasta}'");

        $arrPagoDetalle = $queryBuilder->getQuery()->getSingleResult();
        if($arrPagoDetalle) {
            if($arrPagoDetalle['valor']) {
                return $arrPagoDetalle['valor'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function descuentoPension($codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('SUM(pd.vrPago) AS valor')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'p')
            ->where("p.codigoContratoFk = {$codigoContrato}")
            ->andWhere("c.pension = 1")
            ->andWhere("p.fechaDesdeContrato >='{$fechaDesde}'")
            ->andWhere("p.fechaDesdeContrato <='{$fechaHasta}'");

        $arrPagoDetalle = $queryBuilder->getQuery()->getSingleResult();
        if($arrPagoDetalle) {
            if($arrPagoDetalle['valor']) {
                return $arrPagoDetalle['valor'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function embargos($raw)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('pd')
            ->leftJoin('pd.pagoRel', 'p')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.embargoRel', 'e')
            ->where('c.embargo = 1')
            ->andWhere("e.estadoActivo = 1")
            ->andWhere("p.fechaDesde >= '{$raw['fechaDesde']} 00:00:00'")
            ->andWhere("p.fechaHasta <= '{$raw['fechaHasta']} 23:59:59'")
            ->orderBy('pd.codigoPagoDetallePk','DESC');


        return $queryBuilder->getQuery()->getResult();
    }

    public function creditosPago($raw)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->addSelect('pd.codigoPagoDetallePk')
            ->addSelect('pt.nombre as pagoTipoNombre')
            ->addSelect('p.numero as pagoNumero')
            ->addSelect('p.codigoEmpleadoFk as pagoCodigoEmpleadoFk')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('g.nombre as grupoNombre')
            ->addSelect('pd.codigoConceptoFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('p.fechaDesde as pagoFechaDesde')
            ->addSelect('p.fechaHasta as pagoFechaHasta')
            ->addSelect('pd.vrPagoOperado')
            ->addSelect('pd.vrPago')
            ->addSelect('pd.horas')
            ->addSelect('pd.dias')
            ->addSelect('pd.porcentaje')
            ->addSelect('pd.vrIngresoBaseCotizacion')
            ->addSelect('pd.vrIngresoBasePrestacion')
            ->addSelect('pd.codigoCreditoFk')
            ->addSelect('c.pension')
            ->addSelect('c.salud')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'p')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.pagoTipoRel', 'pt')
            ->leftJoin('p.grupoRel', 'g')
            ->where('c.credito = 1')
            ->andWhere("p.fechaDesde >= '{$raw['fechaDesde']} 00:00:00'")
            ->andWhere("p.fechaHasta <= '{$raw['fechaHasta']} 23:59:59'")
            ->orderBy('pd.codigoPagoDetallePk','DESC');
        return $queryBuilder->getQuery()->getResult();

    }

    public function listaIbp($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT p.numero as numero, pd.vrIngresoBasePrestacion as vrIngresoPrestacion,p.fechaDesde as fechaDesde, 100 as porcentaje, pc.nombre as concepto,
         p.fechaHasta as fechaHasta FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.conceptoRel pc "
            . "WHERE p.estadoAprobado = 1 AND p.codigoContratoFk = " . $codigoContrato . "AND  pd.vrIngresoBasePrestacion > 0"
            . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibp = $arrayResultado;
        if ($ibp == null) {
            $ibp = 0;
        }
        return $ibp;
    }

    public function listaibpSuplementario($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT p.numero as numero, pd.vrSuplementario as vrIngresoPrestacion,p.fechaDesdePago as fechaDesde, 100 as porcentaje, pc.nombre as concepto,
         p.fechaHastaPago as fechaHasta FROM  App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p pd.pagoConceptoRel pc "
            . "WHERE p.estadoPagado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $suplementario = $arrayResultado;
        return $suplementario;
    }

    public function listaIbpSuplementarioVacaciones($fechaDesde, $fechaHasta, $codigoContrato, $aplicaPorcentajeVacacionSuplementario = 0)
    {
        $em = $this->getEntityManager();
        $dql = $em->createQueryBuilder()->from("App\Entity\RecursoHumano\RhuPagoDetalle", "pd")
            ->join("pd.pagoRel", "p")
            ->join("pd.pagoConceptoRel", "pc")
            ->select("p.numero as numero")
            ->addSelect("pd.vrPagoOperado as vrIngresoPrestacion")
            ->addSelect("(pc.porcentajeVacaciones / 100) as porcentaje")
            ->addSelect("p.fechaDesdePago as fechaDesde")
            ->addSelect("p.fechaHastaPago as fechaHasta")
            ->addSelect("pc.nombre as concepto")
            ->where("pc.vacacionSuplementario = 1")
            ->andWhere("p.estadoPagado = 1")
            ->andWhere("p.codigoContratoFk = {$codigoContrato}")
            ->andWhere("p.fechaDesdePago >= '{$fechaDesde}'")
            ->andWhere("p.fechaHastaPago <= '{$fechaHasta}'");
//        $dql = "SELECT (p.numero as numero, pd.vrPagoOperado as vrIngresoPrestacion, (pc.porcentajeVacaciones / 100) as porcentaje,
//        p.fechaDesdePago as fechaDesde, p.fechaHastaPago as fechaHasta, pc.nombre as concepto ) FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
//            . "WHERE pc.vacacionSuplementario = 1 AND p.estadoPagado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
//            . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        if ($aplicaPorcentajeVacacionSuplementario == 1) {//Se validan los conceptos que aplican para las vacaciones de disfrute.
//            $dql .= " AND pc.aplicaPorcentajeVacacionSuplementario = 1";
            $dql->andWhere("pc.aplicaPorcentajeVacacionSuplementario = 1");
        }
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $suplementario = $arrayResultado;
        return $suplementario;
    }

}