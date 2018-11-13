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
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->autorizarProgramacion();

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
        $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaDesdeContrato());
        $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaHastaContrato());

        $arrDatosGenerales = array( 'ingresoBaseCotizacion' => 0,
                                    'ingresoBasePrestacion' => 0,
                                    'neto' => 0);
        $valorDia = $arContrato->getVrSalario() / 30;
        $valorHora = $valorDia / $arContrato->getFactorHorasDia();
        $auxilioTransporte = $arConfiguracion['vrAuxilioTransporte'];
        $diaAuxilioTransporte = $auxilioTransporte / 30;
        $salarioMinimo = $arConfiguracion['vrSalarioMinimo'];

        //Horas
        $arrHoras = $this->getHoras($arProgramacionDetalle);
        foreach ($arrHoras AS $arrHora) {
            if ($arrHora['cantidad'] > 0) {
                /** @var  $arConcepto RhuConcepto */
                $arConcepto = $arConceptoHora[$arrHora['clave']]->getConceptoRel();
                $arPagoDetalle = new RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $valorHoraDetalle = ($valorHora *  $arConcepto->getPorcentaje()) / 100;
                $pagoDetalle = $valorHoraDetalle * $arrHora['cantidad'];
                $arPagoDetalle->setVrHora($valorHoraDetalle);
                $arPagoDetalle->setPorcentaje($arConcepto->getPorcentaje());
                $arPagoDetalle->setConceptoRel($arConcepto);
                $arPagoDetalle->setHoras($arrHora['cantidad']);
                $arPagoDetalle->setDias($arProgramacionDetalle->getDias());
                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                $em->persist($arPagoDetalle);
            }
        }

        //Auxilio de transporte
        if ($arContrato->getAuxilioTransporte() == 1) {
            $arConcepto = $em->getRepository(RhuConcepto::class)->find($arConfiguracion['codigoConceptoAuxilioTransporteFk']);
            $pagoDetalle = round($diaAuxilioTransporte * $arProgramacionDetalle->getDiasTransporte());
            $arPagoDetalle = new RhuPagoDetalle();
            $arPagoDetalle->setPagoRel($arPago);
            $arPagoDetalle->setConceptoRel($arConcepto);
            $arPagoDetalle->setDias($arProgramacionDetalle->getDiasTransporte());
            $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
            $em->persist($arPagoDetalle);
        }

        //Salud
        $arSalud = $arContrato->getSaludRel();
        $porcentajeSalud = $arSalud->getPorcentajeEmpleado();
        if($porcentajeSalud > 0) {
            $ingresoBaseCotizacionSalud = $arrDatosGenerales['ingresoBaseCotizacion'];
            $arConcepto = $arSalud->getConceptoRel();
            if($arConcepto) {
                /*
                 * La base de aportes a seguridad social tanto en salud como en pensión,
                 * no puede ser inferior al salario mínimo ni superior a los 25 salarios mínimos mensuales.
                 * Esta limitación está dada por el artículo 5 de la ley 797 de 2003, reglamentado por el decreto 510 de 2003 en su artículo 3:
                 */
                if ($ingresoBaseCotizacionSalud > ($salarioMinimo * 25)) {
                    $ingresoBaseCotizacionSalud = $salarioMinimo * 25;
                }

                $pagoDetalle = ($ingresoBaseCotizacionSalud * $porcentajeSalud) / 100;
                $pagoDetalle = round($pagoDetalle);

                $arPagoDetalle = new RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $arPagoDetalle->setConceptoRel($arConcepto);
                $arPagoDetalle->setPorcentaje($porcentajeSalud);
                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                $em->persist($arPagoDetalle);
            }
        }



        $arPago->setVrNeto($arrDatosGenerales['neto']);
        $em->persist($arPago);
        return $arrDatosGenerales['neto'];
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
        $arrHoras['D'] = array('tipo' => 'D', 'cantidad' => $arProgramacionDetalle->getHorasDiurnas(), 'clave' => 0);
        $arrHoras['N'] = array('tipo' => 'N', 'cantidad' => $arProgramacionDetalle->getHorasNocturnas(), 'clave' => 7);
        return $arrHoras;
    }

    private function getValoresPagoDetalle(&$arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle) {
        $arPagoDetalle->setVrPago($pagoDetalle);
        $pagoDetalleOperado = $pagoDetalle * $arConcepto->getOperacion();
        $arPagoDetalle->setVrPagoOperado($pagoDetalleOperado);
        $arPagoDetalle->setOperacion($arConcepto->getOperacion());
        if ($arConcepto->getOperacion() == -1) {
            $arPagoDetalle->setVrDeduccion($pagoDetalle);
        } else {
            $arPagoDetalle->setVrDevengado($pagoDetalle);
        }
        $arrDatosGenerales['neto'] += $pagoDetalleOperado;

        if($arConcepto->getGeneraIngresoBaseCotizacion()) {
            $arrDatosGenerales['ingresoBaseCotizacion'] += $pagoDetalleOperado;
            $arPagoDetalle->setVrIngresoBaseCotizacion($pagoDetalleOperado);
        }

        if($arConcepto->getGeneraIngresoBasePrestacion()) {
            $arrDatosGenerales['ingresoBaseCotizacion'] += $pagoDetalleOperado;
            $arPagoDetalle->setVrIngresoBasePrestacion($pagoDetalleOperado);
        }
    }

}