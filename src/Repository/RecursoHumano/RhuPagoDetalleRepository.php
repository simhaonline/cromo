<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuPagoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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
                ->orderBy("pd.codigoConceptoFk", "ASC");
        } else {
            $query = null;
        }
        return $query ? $query->getQuery()->execute() : null;
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
            ->where("p.estadoEgreso = 1")
            ->andWhere('p.codigoContratoFk = ' . $codigoContrato)
            ->andWhere("p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaHasta <= '" . $fechaHasta . "'");
        $arrayResultado = $query->getQuery()->getSingleResult();
        if ($arrayResultado) {
            if($arrayResultado['ibc']) {
                $arrIbc['ibc'] = $arrayResultado['ibc'];
            }
            if($arrayResultado['horas']) {
                $arrIbc['horas'] = $arrayResultado['horas'];
            }
        }
        $query = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('SUM(pd.vrPago) as deduccionFondo')
            ->leftJoin('pd.pagoRel', 'p')
            ->leftJoin('pd.conceptoRel', 'c')
            ->where("p.estadoEgreso = 1")
            ->andWhere('p.codigoContratoFk = ' . $codigoContrato)
            ->andWhere("p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaHasta <= '" . $fechaHasta . "'")
            ->andWhere("c.fondoSolidaridadPensional = 1");
        $arrayResultado = $query->getQuery()->getSingleResult();
        if ($arrayResultado) {
            if($arrayResultado['deduccionFondo']) {
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
            ->addSelect('pd')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'pa');

        if ($session->get('filtroRhuInformePagoDetalleConcepto') != null) {
            $queryBuilder->andWhere("pd.codigoConceptoFk = '{$session->get('filtroRhuInformePagoDetalleConcepto')}'");
        }
        if ($session->get('filtroRhuInformePagoDetalleCodigoEmpleado') != null) {
            $queryBuilder->andWhere("pa.codigoEmpleadoFk = {$session->get('filtroRhuInformePagoDetalleCodigoEmpleado')}");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("pa.fechaDesde >= '{$session->get('filtroRhuInformePagoDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformePagoDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("pa.fechaHasta <= '{$session->get('filtroRhuInformePagoDetalleFechaHasta')} 23:59:59'");
        }

        return $queryBuilder->getQuery()->getResult();

    }

    public function recargosNocturnos($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(pd.vrIngresoBaseCotizacionAdicional) as recagosNocturnos FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.conceptoRel pc "
            . "WHERE pc.recargoNocturno = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $recargosNocturnos = $arrayResultado[0]['recagosNocturnos'];
        if ($recargosNocturnos == null) {
            $recargosNocturnos = 0;
        }
        return $recargosNocturnos;
    }

    public function recargosNocturnosIbp($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select("SUM(pd.vrIngresoBasePrestacion  * (pc.porcentajeVacaciones / 100)) as recargoNocturno")
            ->from(RhuPagoDetalle::class, 'pd')
            ->join('pd.pagoRel', 'p')
            ->join('pd.pagoConceptoRel', 'pc')
            ->where("pc.recargoNocturno = 1")
            ->andWhere("p.codigoContratoFk = {$codigoContrato}")
            ->andWhere("p.fechaDesde >= '{$fechaDesde}'")
            ->andWhere("p.fechaHasta <= '{$fechaHasta}'");


        $arrayResultado = $query->getQuery()->getResult();
        $recargosNocturnos = $arrayResultado[0]['recargoNocturno'];
        return $recargosNocturnos;

    }
}