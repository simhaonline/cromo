<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuPagoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPago::class);
    }

    /**
     * @param $codigoProgramacion integer
     */
    public function eliminarPagos($codigoProgramacion)
    {
        $em = $this->getEntityManager();
        $subQuery = $em->createQueryBuilder()->from(RhuPago::class, 'pp')
            ->select('pp.codigoPagoPk')
            ->where("pp.codigoProgramacionFk = {$codigoProgramacion}");

        $em->createQueryBuilder()
            ->delete(RhuPagoDetalle::class, 'pd')
            ->where("pd.codigoPagoFk IN ({$subQuery})")->getQuery()->execute();

        $codigosPagos = implode(',', array_map(function ($v) {
            return $v['codigoPagoPk'];
        }, $subQuery->getQuery()->execute()));


        $em->createQueryBuilder()->delete(RhuPago::class, 'p')
            ->where("p.codigoPagoPk IN ({$codigosPagos})")->getQuery()->execute();
    }

    /**
     * @param $arProgramacionDetalle RhuProgramacionDetalle
     * @param $arProgramacion RhuProgramacion
     * @param $arConceptoHora array
     * @param $usuario string
     * @return int|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function generar($arProgramacionDetalle, $arProgramacion, $arConceptoHora, $usuario)
    {
        $em = $this->getEntityManager();
        $douDeducciones = 0;
        $douDevengado = 0;
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arPago = new RhuPago();
        $arContrato = $em->getRepository(RhuContrato::class)->find($arProgramacionDetalle->getCodigoContratoFk());
        $arPago->setPagoTipoRel($arProgramacion->getPagoTipoRel());
        $arPago->setEmpleadoRel($arProgramacionDetalle->getEmpleadoRel());
        $arPago->setContratoRel($arProgramacionDetalle->getContratoRel());
        $arPago->setProgramacionDetalleRel($arProgramacionDetalle);
        $arPago->setProgramacionRel($arProgramacion);
        $arPago->setVrSalarioContrato($arContrato->getVrSalario());
        $arPago->setUsuario($usuario);
        $arPago->setEntidadPensionRel($arContrato->getEntidadPensionRel());
        $arPago->setEntidadSaludRel($arContrato->getEntidadSaludRel());
        $arPago->setFechaDesde($arProgramacion->getFechaDesde());
        $arPago->setFechaHasta($arProgramacion->getFechaHasta());
        if ($arContrato->getFechaDesde() >= $arPago->getFechaDesde()) {
            $arPago->setFechaDesdeContrato($arContrato->getFechaDesde());
        } else {
            $arPago->setFechaDesdeContrato($arPago->getFechaDesde());
        }
        if ($arContrato->getFechaHasta() <= $arPago->getFechaHasta()) {
            $arPago->setFechaHastaContrato($arContrato->getFechaHasta());
        } else {
            $arPago->setFechaHastaContrato($arPago->getFechaHasta());
        }
        $em->persist($arPago);

        // Calculo de las horas
        $arrHoras = $this->getHoras($arProgramacionDetalle);
        foreach ($arrHoras AS $arrHora) {
            if ($arrHora['valor'] > 0) {
                /** @var  $arConcepto RhuConcepto */
                $arConcepto = $arConceptoHora[$arrHora['clave']]->getConceptoRel();
                $arPagoDetalle = new RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $floValorDia = $arContrato->getVrSalario() / 30;
                $floValorHora = $floValorDia / $arContrato->getFactorHorasDia();
                $floDevengado = $arProgramacionDetalle->getDias() * $floValorDia;
                $arPagoDetalle->setVrHora($floValorHora);
                $arPagoDetalle->setPorcentaje($arConcepto->getPorcentaje());
                $arPagoDetalle->setConceptoRel($arConcepto);
                $arPagoDetalle->setHoras($arrHora['valor']);
                $arPagoDetalle->setOperacion($arConcepto->getOperacion());
                $arPagoDetalle->setVrPago($floDevengado);
                $arPagoDetalle->setVrPagoOperado($floDevengado * $arConcepto->getOperacion());
                if ($arConcepto->getOperacion() == -1) {
                    $arPagoDetalle->setVrDeduccion($floDevengado);
                } else {
                    $arPagoDetalle->setVrDevengado($floDevengado);
                }
                $arPagoDetalle->setDias($arProgramacionDetalle->getDias());
                if ($arPagoDetalle->getOperacion() == 1) {
                    $douDevengado = $douDevengado + $arPagoDetalle->getVrPago();
                }
                $em->persist($arPagoDetalle);
            }
        }
        $douIngresoBasePrestacional = 0;
        $douIngresoBaseCotizacion = 0;
        $douIngresoBaseCotizacionSalud = 0;
        $devengado = 0;
        $devengadoPrestacional = 0;

        // Calculo del auxilio de transporte
        if ($arContrato->getAuxilioTransporte() == 1) {
            $intPagoConceptoTransporte = $arConfiguracion->getCodigoConceptoAuxilioTransporteFk();
            $arConcepto = $em->getRepository(RhuConcepto::class)->find($intPagoConceptoTransporte);
            $duoVrAuxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();
            $douVrDiaTransporte = $duoVrAuxilioTransporte / 30;
            $douPagoDetalle = $douVrDiaTransporte * $arProgramacionDetalle->getDiasTransporte();
            $douPagoDetalle = round($douPagoDetalle);
            $arPagoDetalle = new RhuPagoDetalle();
            $arPagoDetalle->setPagoRel($arPago);
            $arPagoDetalle->setConceptoRel($arConcepto);
            $arPagoDetalle->setHoras(0);
            $arPagoDetalle->setDias($arProgramacionDetalle->getDiasTransporte());
            $arPagoDetalle->setVrHora($douVrDiaTransporte / 8);
            $arPagoDetalle->setVrPago($douPagoDetalle);
            if ($arConcepto->getGeneraIngresoBasePrestacion() == 1) {
                $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
            }
            $arPagoDetalle->setOperacion($arConcepto->getOperacion());
            $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arConcepto->getOperacion());
            $em->persist($arPagoDetalle);
            $arPago->setVrAuxilioTransporte($douPagoDetalle);
        }

        // Calculo de salud


        $douNeto = $douDevengado - $douDeducciones;
        $arPago->setVrNeto($douNeto);
        $em->persist($arPago);
        return $douNeto;
    }

    /**
     * @param $arPago RhuPago
     * @return int|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function liquidar($arPago)
    {
        $em = $this->getEntityManager();
//        $douSalario = 0;
//        $douAuxilioTransporte = 0;
//        $douPension = 0;
//        $douEps = 0;
        $douDeducciones = 0;
        $douDevengado = 0;
//        $douIngresoBaseCotizacion = 0;
//        $douIngresoBasePrestacion = 0;
        $arPagoDetalles = $em->getRepository(RhuPagoDetalle::class)->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            if ($arPagoDetalle->getOperacion() == 1) {
                $douDevengado = $douDevengado + $arPagoDetalle->getVrPago();
            }
        }
        $douNeto = $douDevengado - $douDeducciones;
        $arPago->setVrNeto($douNeto);
        $em->persist($arPago);
        return $douNeto;
    }

    public function getCodigoPagoPk($codigoProgramacionDetalle)
    {
        $query = $this->_em->createQueryBuilder()->from(RhuPago::class, 'p')
            ->select('p.codigoPagoPk')
            ->where("p.codigoProgramacionDetalleFk = {$codigoProgramacionDetalle}")->getQuery()->getOneOrNullResult();
        if ($query) {
            $query = $query['codigoPagoPk'];
        }
        return $query;
    }

    /**
     * @param $arProgramacionDetalle
     * @return mixed
     */
    private function getHoras($arProgramacionDetalle)
    {
        $arrHoras['D'] = array('tipo' => 'D', 'valor' => $arProgramacionDetalle->getHorasDiurnas(), 'clave' => 0);
        $arrHoras['N'] = array('tipo' => 'N', 'valor' => $arProgramacionDetalle->getHorasNocturnas(), 'clave' => 7);
        return $arrHoras;
    }


}