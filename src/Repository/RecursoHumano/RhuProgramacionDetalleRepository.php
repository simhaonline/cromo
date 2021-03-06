<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuProgramacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuProgramacionDetalle::class);
    }

    /**
     * @param $arrSeleccionados
     * @param $arProgramacion RhuProgramacion
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados){
        $em = $this->getEntityManager();
        foreach ($arrSeleccionados as $codigoProgramacionDetalle){
            $arProgramacionDetalle = $em->getRepository(RhuProgramacionDetalle::class)->find($codigoProgramacionDetalle);
            if($arProgramacionDetalle){
                if($arProgramacionDetalle->getCodigoSoporteContratoFk()) {
                    $arAdicionales = $em->getRepository(RhuAdicional::class)->findBy(['codigoSoporteContratoFk' => $arProgramacionDetalle->getCodigoSoporteContratoFk()]);
                    foreach ($arAdicionales as $arAdicional) {
                        $em->remove($arAdicional);
                    }
                }
                $em->remove($arProgramacionDetalle);
            }
        }
        $em->flush();
    }


    public function contarDetalles($codigoProgramacion){
        $this->_em->createQueryBuilder()->from(RhuProgramacionDetalle::class,'pd')
            ->select('COUNT(pd.codigoProgramacionDetallePk)')
            ->where('pd.codigoProgramacionFk =' . $codigoProgramacion)->getQuery()->execute();
    }

    public function resumen($id){
        return $this->_em->createQueryBuilder()->from(RhuProgramacionDetalle::class,'pd')
            ->leftJoin('pd.empleadoRel','e')
            ->leftJoin('pd.contratoRel','c')
            ->leftJoin('c.cargoRel','ca')
            ->select('e.nombreCorto')
            ->addSelect('pd.vrSalario')
            ->addSelect('pd.codigoProgramacionDetallePk')
            ->addSelect('c.fechaDesde as fechaDesdeContrato')
            ->addSelect('c.fechaHasta as fechaHastaContrato')
            ->addSelect('pd.fechaDesde')
            ->addSelect('pd.fechaHasta')
            ->addSelect('ca.nombre')
            ->where("pd.codigoProgramacionDetallePk = {$id}")->getQuery()->execute()[0];
    }

    public function lista($raw,$id){

        $filtros = $raw['filtros'] ?? null;
        $identificacion = null;
        $estadoMarcado = null;
        $pagosNegativos = null;
        if ($filtros) {
            $identificacion = $filtros['identificacion'] ?? null;
            $estadoMarcado = $filtros['estadoMarcado'] ?? null;
            $pagosNegativos = $filtros['pagosNegativos'] ?? null;
        }
        $queryBuilder= $this->_em->createQueryBuilder()->from(RhuProgramacionDetalle::class,'pd')
            ->select('pd.codigoProgramacionDetallePk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('pd.codigoContratoFk')
            ->addSelect('pd.fechaDesdeContrato')
            ->addSelect('pd.fechaHastaContrato')
            ->addSelect('pd.vrSalario')
            ->addSelect('pd.vrSalarioPrima')
            ->addSelect('pd.vrSalarioCesantia')
            ->addSelect('pd.vrNeto')
            ->addSelect('pd.vrAnticipo')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasDescanso')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasFestivasDiurnas')
            ->addSelect('pd.horasFestivasNocturnas')
            ->addSelect('pd.horasExtrasOrdinariasDiurnas')
            ->addSelect('pd.horasExtrasOrdinariasNocturnas')
            ->addSelect('pd.horasExtrasFestivasDiurnas')
            ->addSelect('pd.horasExtrasFestivasNocturnas')
            ->addSelect('pd.horasRecargo')
            ->addSelect('pd.horasRecargoNocturno')
            ->addSelect('pd.horasRecargoFestivoDiurno')
            ->addSelect('pd.horasRecargoFestivoNocturno')
            ->addSelect('pd.codigoEmpleadoFk')
            ->addSelect('pd.dias')
            ->addSelect('pd.diasAusentismo')
            ->addSelect('pd.marca')
            ->leftJoin('pd.empleadoRel','e')
            ->where("pd.codigoProgramacionFk = {$id}");
        if ($identificacion) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$identificacion}'");
        }
        switch ($estadoMarcado) {
            case '0':
                $queryBuilder->andWhere("pd.marca = 0");
                break;
            case '1':
                $queryBuilder->andWhere("pd.marca = 1");
                break;
        }

        switch ($pagosNegativos) {
            case '0':
                $queryBuilder->andWhere("pd.vrNeto < 0");
                break;
            case '1':
                $queryBuilder->andWhere("pd.vrNeto > 0");
                break;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function listaEliminarTodo($id){
        return $this->_em->createQueryBuilder()->from(RhuProgramacionDetalle::class,'pd')
            ->select('pd.codigoProgramacionDetallePk')
            ->where("pd.codigoProgramacionFk = {$id}")->getQuery()->execute();
    }

    /**
     * @param $arProgramacionDetalle RhuProgramacionDetalle
     */
    public function actualizar($arProgramacionDetalle, $usuario){
        $em = $this->getEntityManager();
        /** @var  $arProgramacion RhuProgramacion */
        $arProgramacion = $em->getRepository(RhuProgramacion::class)->find($arProgramacionDetalle->getCodigoProgramacionFk());
        $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoProgramacionDetalleFk' => $arProgramacionDetalle->getCodigoProgramacionDetallePk()));
        foreach ($arPagos as $arPago) {
            $arPagosDetalles = $em->getRepository(RhuPagoDetalle::class)->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
            foreach ($arPagosDetalles as $arPagoDetalle) {
                $em->remove($arPagoDetalle);
            }
            $em->remove($arPago);
        }
        $arProgramacion->setVrNeto($arProgramacion->getVrNeto() - $arProgramacionDetalle->getVrNeto());
        $em->persist($arProgramacion);
        $arProgramacionDetalle->setVrNeto(0);
        $em->getRepository(RhuProgramacionDetalle::class)->asignarValores($arProgramacionDetalle, $arProgramacion, $arProgramacionDetalle->getContratoRel());
        $em->persist($arProgramacionDetalle);
        $em->flush();
        $em->getRepository(RhuProgramacion::class)->generar($arProgramacion, $arProgramacionDetalle->getCodigoProgramacionDetallePk(), $usuario);
    }

    public function asignarValores(&$arProgramacionDetalle, $arProgramacion, $arContrato) {
        $em = $this->getEntityManager();
        if ($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'APR' || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'PRA') {
            $arProgramacionDetalle->setDescuentoPension(0);
            $arProgramacionDetalle->setDescuentoSalud(0);
            $arProgramacionDetalle->setPagoAuxilioTransporte(0);
        }
        if ($arContrato->getCodigoPensionFk() == 'PEN') {
            $arProgramacionDetalle->setDescuentoPension(0);
        }

        $fechaDesde = $em->getRepository(RhuProgramacion::class)->fechaDesdeContrato($arProgramacion->getFechaDesde(), $arContrato->getFechaDesde());
        $fechaHasta = $em->getRepository(RhuProgramacion::class)->fechaHastaContrato($arProgramacion->getFechaHasta(), $arContrato->getFechaHasta(), $arContrato->getIndefinido());
        $dias = $fechaDesde->diff($fechaHasta)->days + 1;
        $arProgramacionDetalle->setFechaDesde($arProgramacion->getFechaDesde());
        $arProgramacionDetalle->setFechaHasta($arProgramacion->getFechaHasta());
        $arProgramacionDetalle->setFechaDesdeContrato($fechaDesde);
        $arProgramacionDetalle->setFechaHastaContrato($fechaHasta);
        $arrIbc = $em->getRepository(RhuPagoDetalle::class)->ibcMes($fechaDesde->format('Y'), $fechaDesde->format('m'), $arContrato->getCodigoContratoPk());
        $arProgramacionDetalle->setVrIbcAcumulado($arrIbc['ibc']);
        $arProgramacionDetalle->setVrDeduccionFondoPensionAnterior($arrIbc['deduccionAnterior']);

        //dias vacaciones
        $arrVacaciones = $em->getRepository(RhuVacacion::class)->dias($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacion->getFechaDesde(), $arProgramacion->getFechaHasta());
        $diasVacaciones = $arrVacaciones['dias'];
        if ($diasVacaciones > 0) {
            $arProgramacionDetalle->setDiasVacaciones($diasVacaciones);
        }

        $diasLicencia = 0;
        $arLicencias = $em->getRepository(RhuLicencia::class)->periodo($arProgramacionDetalle->getFechaDesdeContrato(), $arProgramacionDetalle->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());
        foreach ($arLicencias as $arLicencia) {
            $fechaDesde = $arProgramacionDetalle->getFechaDesdeContrato();
            $fechaHasta = $arProgramacionDetalle->getFechaHasta();
            if ($arLicencia->getFechaDesde() > $fechaDesde) {
                $fechaDesde = $arLicencia->getFechaDesde();
            }
            if ($arLicencia->getFechaHasta() < $fechaHasta) {
                $fechaHasta = $arLicencia->getFechaHasta();
            }
            $intDias = $fechaDesde->diff($fechaHasta);
            $intDias = $intDias->format('%a');
            $intDias += 1;
            $diasLicencia += $intDias;
        }
        if ($diasLicencia > 0) {
            $arProgramacionDetalle->setDiasLicencia($diasLicencia);
        }

        $diasIncapacidad = 0;
        $arIncapacidades = $em->getRepository(RhuIncapacidad::class)->periodo($arProgramacionDetalle->getFechaDesdeContrato(), $arProgramacionDetalle->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());
        foreach ($arIncapacidades as $arIncapacidad) {
            $fechaDesde = $arProgramacionDetalle->getFechaDesdeContrato();
            $fechaHasta = $arProgramacionDetalle->getFechaHasta();
            if ($arIncapacidad->getFechaDesde() > $fechaDesde) {
                $fechaDesde = $arIncapacidad->getFechaDesde();
            }
            if ($arIncapacidad->getFechaHasta() < $fechaHasta) {
                $fechaHasta = $arIncapacidad->getFechaHasta();
            }
            $intDias = $fechaDesde->diff($fechaHasta);
            $intDias = $intDias->format('%a');
            $intDias += 1;
            $diasIncapacidad += $intDias;
        }
        if ($diasIncapacidad > 0) {
            $arProgramacionDetalle->setDiasIncapacidad($diasIncapacidad);
        }

        $diasNovedad = $diasIncapacidad + $diasLicencia + $diasVacaciones;
        $dias = $dias - $diasNovedad;
        $horas = $dias * $arContrato->getFactorHorasDia();
        $arProgramacionDetalle->setDias($dias);
        $arProgramacionDetalle->setDiasTransporte($dias);
        $arProgramacionDetalle->setHorasDiurnas($horas);
        $vrDia = $arContrato->getVrSalarioPago() / 30;
        $vrHora = $vrDia / $arContrato->getFactorHorasDia();
        $arProgramacionDetalle->setFactorHorasDia($arContrato->getFactorHorasDia());
        $arProgramacionDetalle->setVrDia($vrDia);
        $arProgramacionDetalle->setVrHora($vrHora);

    }

    public function exportar($id)
    {

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuProgramacionDetalle::class, 'pd')
            ->select('pd.codigoProgramacionDetallePk')
            ->addSelect('pd.codigoEmpleadoFk as COD')
            ->addSelect('e.numeroIdentificacion as DOC')
            ->addSelect('e.nombreCorto as NOMBRE')
            ->addSelect('pd.codigoContratoFk AS CON')
            ->addSelect('pd.fechaDesdeContrato as DESDE')
            ->addSelect('pd.fechaHastaContrato as HASTA')
            ->addSelect('pd.vrSalario AS SALARIO')
            ->addSelect('pd.vrNeto AS NETO')
            ->addSelect('pd.horasDiurnas as HD')
            ->addSelect('pd.horasNocturnas as HN')
            ->addSelect('pd.horasFestivasDiurnas as HFD')
            ->addSelect('pd.horasFestivasNocturnas as HFN')
            ->addSelect('pd.horasExtrasOrdinariasDiurnas as HEOD')
            ->addSelect('pd.horasExtrasOrdinariasNocturnas as HEON')
            ->addSelect('pd.horasExtrasFestivasDiurnas as HEFD')
            ->addSelect('pd.horasExtrasFestivasNocturnas as HEFN')
            ->addSelect('pd.horasRecargoNocturno as RN')
            ->addSelect('pd.horasRecargoFestivoDiurno as RFD')
            ->addSelect('pd.horasRecargoFestivoNocturno as RFN')
            ->leftJoin('pd.empleadoRel','e')
            ->where("pd.codigoProgramacionFk = {$id}");

        return $queryBuilder->getQuery();
    }

    public function pagoPrimaDeduccion($codigoEmpleado, $tipoPago)
    {
        $em = $this->getEntityManager();
        $strSql = "SELECT ppd.codigoProgramacionDetallePk, ppd.vrNeto, ppd.diasAusentismo, ppd.dias, ppd.fechaHasta, ppd.fechaHastaContrato FROM App\Entity\RecursoHumano\RhuProgramacionDetalle ppd JOIN ppd.programacionRel pp" .
            " WHERE ppd.codigoEmpleadoFk = {$codigoEmpleado} AND pp.codigoPagoTipoFk={$tipoPago} ORDER BY ppd.fechaHasta DESC";
        $query = $em->createQuery($strSql);
        //$query->getMaxResults(1);
        $arRegistros = $query->getResult();
        if ($arRegistros) {
            $arRegistros = $arRegistros[0];
        }
        return $arRegistros;
    }

    public function empleadosProgramacion($codigoProgramacion) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuProgramacionDetalle::class, 'pd')
            ->select('pd.codigoEmpleadoFk')
            ->addSelect('e.codigoIdentificacionFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.correo')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.nombre1')
            ->addSelect('e.nombre2')
            ->addSelect('e.apellido1')
            ->addSelect('e.apellido2')
            ->addSelect('e.telefono')
            ->addSelect('e.celular')
            ->addSelect('e.direccion')
            ->leftJoin('pd.empleadoRel', 'e')
            ->groupBy('pd.codigoEmpleadoFk')
            ->where("pd.codigoProgramacionFk = {$codigoProgramacion}");
        $arEmpleados = $queryBuilder->getQuery()->getResult();
        return $arEmpleados;
    }

    /**
     * @param $arProgramacion RhuProgramacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminarTodoDetalles($arProgramacion){
        $this->_em->createQueryBuilder()
            ->delete(RhuProgramacionDetalle::class,'pd')
            ->where("pd.codigoProgramacionFk = {$arProgramacion->getCodigoProgramacionPk()}")->getQuery()->execute();
        $arProgramacion->setEmpleadosGenerados(0);
        $cantidad = $this->_em->getRepository(RhuProgramacion::class)->getCantidadRegistros($arProgramacion->getCodigoProgramacionPk());
        $arProgramacion->setCantidad($cantidad);
        $this->_em->persist($arProgramacion);
        $this->_em->flush();
    }

    public function actualizarDetalles($arrHoras)
    {
        $em = $this->getEntityManager();
        foreach ($arrHoras as $clave => $valor){
            if ($clave != "form"){
                $arProgramacionDetalle = $em->getRepository(RhuProgramacionDetalle::class)->find($clave);
                $arProgramacionDetalle->setHorasDiurnas($valor['horasDiurnas']);
                $arProgramacionDetalle->setHorasDescanso($valor['horasDescanso']);
                $arProgramacionDetalle->setHorasNocturnas($valor['horasNocturnas']);
                $arProgramacionDetalle->setHorasFestivasDiurnas($valor['horasFestivasDiurnas']);
                $arProgramacionDetalle->setHorasFestivasNocturnas($valor['horasFestivasNocturnas']);
                $arProgramacionDetalle->setHorasExtrasOrdinariasDiurnas($valor['horasExtrasOrdinariasDiurnas']);
                $arProgramacionDetalle->setHorasExtrasOrdinariasNocturnas($valor['horasExtrasOrdinariasNocturnas']);
                $arProgramacionDetalle->setHorasExtrasFestivasDiurnas($valor['horasExtrasFestivasDiurnas']);
                $arProgramacionDetalle->setHorasExtrasFestivasNocturnas($valor['horasExtrasFestivasNocturnas']);
                $em->persist($arProgramacionDetalle);
            }
        }
        $em->flush();
    }

}