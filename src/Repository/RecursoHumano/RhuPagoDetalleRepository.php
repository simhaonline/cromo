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

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->addSelect('pd')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.pagoRel', 'pa');

        if ($session->get('filtroRhuInformePagoConcepto') != null) {
            $queryBuilder->andWhere("pd.codigoConceptoFk = '{$session->get('filtroRhuInformePagoConcepto')}'");
        }
        if ($session->get('filtroRhuInformePagoCodigoEmpleado') != null) {
            $queryBuilder->andWhere("pa.codigoEmpleadoFk = {$session->get('filtroRhuInformePagoCodigoEmpleado')}");
        }
        if ($session->get('filtroRhuInformePagoFechaDesde') != null) {
            $queryBuilder->andWhere("pa.fechaDesde >= '{$session->get('filtroRhuInformePagoFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformePagoFechaHasta') != null) {
            $queryBuilder->andWhere("pa.fechaHasta <= '{$session->get('filtroRhuInformePagoFechaHasta')} 23:59:59'");
        }



        return $queryBuilder->getQuery()->getResult();

    }
}