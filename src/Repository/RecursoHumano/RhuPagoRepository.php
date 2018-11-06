<?php

namespace App\Repository\RecursoHumano;

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
    public function eliminarTodo($codigoProgramacion){
        $this->_em->createQueryBuilder()->delete(RhuPago::class,'p')
            ->leftJoin('p.programacionDetalleRel','prd')
            ->where("prd.codigoProgramacionFk = {$codigoProgramacion}")->getQuery()->execute();
    }

    /**
     * @param $arProgramacionDetalle RhuProgramacionDetalle
     * @param $arProgramacion RhuProgramacion
     */
    public function generar($arProgramacionDetalle, $arProgramacion, $arConceptoHora){
        $em = $this->getEntityManager();
        //$arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arPago = new RhuPago();
        $arContrato = $em->getRepository(RhuContrato::class)->generarPago($arProgramacionDetalle->getCodigoContratoFk());
        $arPago->setPagoTipoRel($arProgramacion->getPagoTipoRel());
        $arPago->setEmpleadoRel($arProgramacionDetalle->getEmpleadoRel());
        $arPago->setContratoRel($arProgramacionDetalle->getContratoRel());
        $arPago->setProgramacionDetalleRel($arProgramacionDetalle);
        $em->persist($arPago);

        $arrHoras = $this->getHoras($arProgramacionDetalle);
        foreach ($arrHoras AS $arrHora) {
            if($arrHora['valor'] > 0) {
                $arPagoDetalle = new RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $arPagoDetalle->setConceptoRel($arConceptoHora[$arrHora['clave']]->getConceptoRel());
                $em->persist($arPagoDetalle);
            }
        }

        /*$arPago->setFechaDesde($arProgramacion->getFechaDesde());
        $arPago->setFechaHasta($arProgramacion->getFechaHasta());
        $arPago->setFechaDesde($arProgramacionDetalle->getFechaDesdePago());
        $arPago->setFechaHasta($arProgramacionDetalle->getFechaHastaPago());
        $arPago->setVrSalarioContrato($arProgramacionDetalle->getVrSalario());
        $arPago->setUsuario($arProgramacion->getUsuario());
        $arPago->setComentario($arProgramacionDetalle->getComentarios());
         */
        //Parametros generales
/*        $intHorasLaboradas = $arProgramacionDetalle->getHorasPeriodoReales();
        $horasDiurnas = $arProgramacionDetalle->getHorasDiurnas();
        $intDiasTransporte = $arProgramacionDetalle->getDiasReales();
        $intFactorDia = $arProgramacionDetalle->getFactorDia();
        $douVrDia = $arProgramacionDetalle->getVrDia();
        $douVrHora = $arProgramacionDetalle->getVrHora();
        $douVrSalarioMinimo = $arConfiguracion->getVrSalario();
        $douVrHoraSalarioMinimo = ($douVrSalarioMinimo / 30) / 8;
        $douIngresoBasePrestacional = 0;
        $douIngresoBaseCotizacion = 0;
        $douIngresoBaseCotizacionSalud = 0;
        $devengado = 0;
        $devengadoPrestacional = 0;
        $salud = 0;
        $pension = 0;
        $transporte = 0;
*/
    }

    /**
     * @param $arProgramacionDetalle RhuProgramacionDetalle
     */
    private function getHoras($arProgramacionDetalle) {
        $arrHoras['D'] = array('tipo' => 'D', 'valor' => $arProgramacionDetalle->getHorasDiurnas(), 'clave' => 0);
        $arrHoras['N'] = array('tipo' => 'N', 'valor' => $arProgramacionDetalle->getHorasNocturnas(), 'clave' => 7);
        return $arrHoras;
    }

}