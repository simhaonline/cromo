<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
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
    public function eliminarTodo($codigoProgramacion)
    {
        $this->_em->createQueryBuilder()->delete(RhuPago::class, 'p')
            ->leftJoin('p.programacionDetalleRel', 'prd')
            ->where("prd.codigoProgramacionFk = {$codigoProgramacion}")->getQuery()->execute();
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
//        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
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
                $arPagoDetalle->setDias($arProgramacionDetalle->getDias());
                if ($arPagoDetalle->getOperacion() == 1) {
                    $douDevengado = $douDevengado + $arPagoDetalle->getVrPago();
                }
                $em->persist($arPagoDetalle);
            }
        }
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