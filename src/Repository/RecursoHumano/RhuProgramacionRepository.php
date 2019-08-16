<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComCuentaPagarTipo;
use App\Entity\Compra\ComProveedor;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConsecutivo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PhpParser\Node\Expr\New_;
use Symfony\Bridge\Doctrine\RegistryInterface;


class RhuProgramacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuProgramacion::class);
    }

    /**
     * @param $arrSeleccionados array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if (is_array($arrSeleccionados) && count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuProgramacion::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNeto($id)
    {
        return $this->_em->createQueryBuilder()
            ->from(RhuProgramacion::class, 'p')
            ->select('p.vrNeto')
            ->where("p.codigoProgramacionPk = {$id}")
            ->getQuery()->getSingleResult();
    }

    /**
     * @param $codigoProgramacion integer
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCantidadRegistros($codigoProgramacion)
    {
        return $this->_em->createQueryBuilder()->from(RhuProgramacionDetalle::class, 'pd')
            ->select('count(pd.codigoProgramacionDetallePk)')
            ->where("pd.codigoProgramacionFk = {$codigoProgramacion}")->getQuery()->getSingleResult()[1];
    }

    /**
     * @param $arProgramacion RhuProgramacion
     * @throws \Doctrine\ORM\ORMException
     */
    public function cargarContratos($arProgramacion)
    {
        $em = $this->getEntityManager();
        $em->getRepository(RhuProgramacionDetalle::class)->eliminarTodoDetalles($arProgramacion);
        $arContratos = $em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select("c")
            ->where("c.codigoGrupoFk = '{$arProgramacion->getCodigoGrupoFk()}'")
            ->andWhere("c.fechaUltimoPago < '{$arProgramacion->getFechaHastaPeriodo()->format('Y-m-d')}'")
            ->andWhere("c.fechaDesde <= '{$arProgramacion->getFechaHastaPeriodo()->format('Y-m-d')}'")
            ->andWhere("(c.fechaHasta >= '{$arProgramacion->getFechaDesde()->format('Y-m-d')}' OR c.indefinido=1)")
            ->getQuery()->execute();

        /** @var $arContrato RhuContrato */
        foreach ($arContratos as $arContrato) {
            $arProgramacionDetalle = new RhuProgramacionDetalle();
            $arProgramacionDetalle->setProgramacionRel($arProgramacion);
            $arProgramacionDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arProgramacionDetalle->setContratoRel($arContrato);
            $arProgramacionDetalle->setVrSalario($arContrato->getVrSalarioPago());

            if ($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'APR' || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'PRA') {
                $arProgramacionDetalle->setDescuentoPension(0);
                $arProgramacionDetalle->setDescuentoSalud(0);
                $arProgramacionDetalle->setPagoAuxilioTransporte(0);
            }
            if ($arContrato->getCodigoPensionFk() == 'PEN') {
                $arProgramacionDetalle->setDescuentoPension(0);
            }

            $fechaDesde = $this->fechaDesdeContrato($arProgramacion->getFechaDesde(), $arContrato->getFechaDesde());
            $fechaHasta = $this->fechaHastaContrato($arProgramacion->getFechaHasta(), $arContrato->getFechaHasta(), $arContrato->getIndefinido());
            $dias = $fechaDesde->diff($fechaHasta)->days + 1;
            $arProgramacionDetalle->setFechaDesde($arProgramacion->getFechaDesde());
            $arProgramacionDetalle->setFechaHasta($arProgramacion->getFechaHasta());
            $arProgramacionDetalle->setFechaDesdeContrato($fechaDesde);
            $arProgramacionDetalle->setFechaHastaContrato($fechaHasta);
            $arrIbc = $em->getRepository(RhuPagoDetalle::class)->ibcMes($fechaDesde->format('Y'), $fechaDesde->format('m'), $arContrato->getCodigoContratoPk());
            $arProgramacionDetalle->setVrIbcAcumulado($arrIbc['ibc']);
            $arProgramacionDetalle->setVrDeduccionFondoPensionAnterior($arrIbc['deduccionAnterior']);
            $arrVacaciones = $em->getRepository(RhuVacacion::class)->diasProgramacion($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacion->getFechaDesde()->format('Y-m-d'), $arProgramacion->getFechaHasta()->format('Y-m-d'));
            $arProgramacionDetalle->setDiasVacaciones($arrVacaciones['dias']);
            $dias -= $arrVacaciones['dias'];
            $horas = $dias * $arContrato->getFactorHorasDia();
            $arProgramacionDetalle->setDias($dias);
            $arProgramacionDetalle->setDiasTransporte($dias);
            $arProgramacionDetalle->setHorasDiurnas($horas);
            $vrDia = $arContrato->getVrSalarioPago() / 30;
            $vrHora = $vrDia / $arContrato->getFactorHorasDia();
            $arProgramacionDetalle->setFactorHorasDia($arContrato->getFactorHorasDia());
            $arProgramacionDetalle->setVrDia($vrDia);
            $arProgramacionDetalle->setVrHora($vrHora);
            $em->persist($arProgramacionDetalle);
        }
        $cantidad = $em->getRepository(RhuProgramacion::class)->getCantidadRegistros($arProgramacion->getCodigoProgramacionPk());
        $arProgramacion->setCantidad($cantidad);
        $arProgramacion->setEmpleadosGenerados(1);
        $em->persist($arProgramacion);
        $em->flush();

    }

    /**
     * @param $arProgramacion RhuProgramacion
     * @param $usuario Usuario
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arProgramacion, $usuario)
    {
        if (!$arProgramacion->getEstadoAutorizado()) {
            $this->generar($arProgramacion, null, $usuario);
        }
    }


    /**
     * @param $arProgramacion RhuProgramacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arProgramacion)
    {
        /**
         * @var $arPago
         */
        $em = $this->getEntityManager();
        if ($arProgramacion->getEstadoAutorizado() == 1 && $arProgramacion->getEstadoAprobado() == 0) {
            $arProgramacion->setEstadoAprobado(1);
            $em->persist($arProgramacion);
            $arConsecutivo = $em->getRepository(RhuConsecutivo::class)->find(1);
            $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()));
            foreach ($arPagos as $arPago) {
                $arPago->setNumero($arConsecutivo->getConsecutivo());
                $arPago->setEstadoAutorizado(1);
                $arPago->setEstadoAprobado(1);
                $em->persist($arPago);
                $arConsecutivo->setConsecutivo($arConsecutivo->getConsecutivo() + 1);
            }
            $em->persist($arConsecutivo);

            //Procesar creditos
            $arPagoDetalleCreditos = $em->getRepository(RhuPagoDetalle::class)->creditos($arProgramacion->getCodigoProgramacionPk());
            foreach ($arPagoDetalleCreditos as $arPagoDetalleCredito) {
                $arPagoDetalle = $em->getRepository(RhuPagoDetalle::class)->find($arPagoDetalleCredito['codigoPagoDetallePk']);
                /** @var  $arCredito RhuCredito */
                $arCredito = $arPagoDetalle->getCreditoRel();
                //Crear credito pago, se guarda el pago en la tabla rhu_pago_credito
                $arPagoCredito = new RhuCreditoPago();
                $arPagoCredito->setCreditoRel($arCredito);
                $arPagoCredito->setPagoDetalleRel($arPagoDetalle);
                $arPagoCredito->setfechaPago(new \ DateTime("now"));
                $arPagoCredito->setCreditoPagoTipoRel($arCredito->getCreditoPagoTipoRel());
                $arPagoCredito->setVrPago($arPagoDetalle->getVrPago());

                //Actualizar el saldo del credito
                $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                $arCredito->setVrSaldo($arCredito->getVrSaldo() - $arPagoDetalleCredito['vrPago']);
                $arCredito->setVrAbonos($arCredito->getVrAbonos() + $arPagoDetalleCredito['vrPago']);
                if ($arCredito->getVrSaldo() <= 0) {
                    $arCredito->setEstadoPagado(1);
                }
                $arPagoCredito->setVrSaldo($arCredito->getVrSaldo());
                $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
                $em->persist($arPagoCredito);
                $em->persist($arCredito);
            }

            //Verificar tercero en cuenta por pagar
            if ($arPago->getPagoTipoRel()->getGeneraTesoreria()) {
                foreach ($arPagos as $arPago) {
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arPago->getCodigoEmpleadoFk());
                    $arTerceroCuentaPagar = $em->getRepository(TesTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arPago->getEmpleadoRel()->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arPago->getEmpleadoRel()->getNumeroIdentificacion()));
                    if ($arTerceroCuentaPagar) {
                        $bancoActual = $arTerceroCuentaPagar->getCodigoBancoFk();
                        $cuentaActual = $arTerceroCuentaPagar->getCuenta();
                        if ($bancoActual != $arPago->getEmpleadoRel()->getCodigoBancoFk()) {
                            $arTerceroCuentaPagar->setBancoRel($arEmpleado->getBancoRel());
                        }
                        if ($cuentaActual != $arEmpleado->getCuenta()) {
                            $arTerceroCuentaPagar->setCuenta($arEmpleado->getCuenta());
                        }
                    }
                    if (!$arTerceroCuentaPagar) {
                        $arTerceroCuentaPagar = new TesTercero();
                        $arTerceroCuentaPagar->setIdentificacionRel($arEmpleado->getIdentificacionRel());
                        $arTerceroCuentaPagar->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arTerceroCuentaPagar->setNombre1($arEmpleado->getNombre1());
                        $arTerceroCuentaPagar->setNombre2($arEmpleado->getNombre2());
                        $arTerceroCuentaPagar->setApellido1($arEmpleado->getApellido1());
                        $arTerceroCuentaPagar->setApellido2($arEmpleado->getApellido2());
                        $arTerceroCuentaPagar->setNombreCorto($arEmpleado->getNombreCorto());
                        $arTerceroCuentaPagar->setCiudadRel($arEmpleado->getCiudadRel());
                        $arTerceroCuentaPagar->setCelular($arEmpleado->getCelular());
                        $arTerceroCuentaPagar->setBancoRel($arEmpleado->getBancoRel());
                        $arTerceroCuentaPagar->setCuenta($arEmpleado->getCuenta());
                        $arTerceroCuentaPagar->setCodigoCuentaTipoFk($arEmpleado->getCodigoCuentaTipoFk());
                    }
                    $em->persist($arTerceroCuentaPagar);

                    $arCuentaPagarTipo = $em->getRepository(TesCuentaPagarTipo::class)->find($arPago->getPagoTipoRel()->getCodigoCuentaPagarTipoFk());
                    $arCuentaPagar = New TesCuentaPagar();
                    $arCuentaPagar->setCuentaPagarTipoRel($arCuentaPagarTipo);
                    $arCuentaPagar->setTerceroRel($arTerceroCuentaPagar);
                    $arCuentaPagar->setBancoRel($arEmpleado->getBancoRel());
                    $arCuentaPagar->setCuenta($arEmpleado->getCuenta());
                    $arCuentaPagar->setNumeroDocumento($arPago->getNumero());
                    $arCuentaPagar->setFecha($arPago->getFechaDesde());
                    $arCuentaPagar->setFechaVence($arPago->getFechaDesde());
                    $arCuentaPagar->setVrSubtotal($arPago->getVrDevengado());
                    $arCuentaPagar->setVrTotal($arPago->getVrNeto());
                    $arCuentaPagar->setVrSaldo($arPago->getVrNeto());
                    $arCuentaPagar->setVrSaldoOperado($arPago->getVrNeto());
                    $arCuentaPagar->setEstadoAutorizado(1);
                    $arCuentaPagar->setEstadoAprobado(1);
                    $arCuentaPagar->setOperacion(1);
                    $em->persist($arCuentaPagar);
                }
            }
            $em->flush();


        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    /**
     * @param $arProgramacion RhuProgramacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arProgramacion)
    {
        $em = $this->getEntityManager();
        if ($arProgramacion->getEstadoAutorizado()) {
            $em->getRepository(RhuPago::class)->eliminarPagos($arProgramacion->getCodigoProgramacionPk());
            $arProgramacion->setEstadoAutorizado(0);
            $arProgramacion->setVrNeto(0);
            $em->persist($arProgramacion);
            $em->flush();
            $this->setVrNeto($arProgramacion->getCodigoProgramacionPk());
        }
    }

    /**
     * @param $arProgramacion RhuProgramacion
     * @throws \Doctrine\ORM\ORMException
     */
    public function liquidar($arProgramacion)
    {
        $em = $this->getEntityManager();
        set_time_limit(0);
        $numeroPagos = 0;
        $douNetoTotal = 0;
//        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arPagos = $em->getRepository(RhuPago::class)->findBy(['codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()]);
        foreach ($arPagos as $arPago) {
            $vrNeto = $em->getRepository(RhuPago::class)->liquidar($arPago);
            $arProgramacionDetalle = $em->getRepository(RhuProgramacionDetalle::class)->find($arPago->getCodigoProgramacionDetalleFk());
            $arProgramacionDetalle->setVrNeto($vrNeto);
            $em->persist($arProgramacionDetalle);
            $douNetoTotal += $vrNeto;
            $numeroPagos++;
        }
        $arProgramacion->setVrNeto($douNetoTotal);
        $arProgramacion->setCantidad($numeroPagos);
        $em->persist($arProgramacion);
        $em->flush();
    }

    private function fechaHastaContrato($fechaHastaPeriodo, $fechaHastaContrato, $indefinido)
    {
        $fechaHasta = $fechaHastaContrato;
        if ($indefinido) {
            $fecha = date_create(date('Y-m-d'));
            date_modify($fecha, '+100000 day');
            $fechaHasta = $fecha;
        }
        if ($fechaHasta > $fechaHastaPeriodo) {
            $fechaHasta = $fechaHastaPeriodo;
        }
        return $fechaHasta;
    }

    private function fechaDesdeContrato($fechaDesdePeriodo, $fechaDesdeContrato)
    {
        $fechaDesde = $fechaDesdeContrato;
        if ($fechaDesdeContrato < $fechaDesdePeriodo) {
            $fechaDesde = $fechaDesdePeriodo;
        }
        return $fechaDesde;
    }

    /**
     * @param $codigoProgramacion int
     */
    private function setVrNeto($codigoProgramacion)
    {
        $this->_em->createQueryBuilder()
            ->update(RhuProgramacionDetalle::class, 'pd')
            ->set('pd.vrNeto', '?1')
            ->where("pd.codigoProgramacionFk = {$codigoProgramacion}")
            ->setParameter('1', 0)
            ->getQuery()->execute();
    }

    public function generar($arProgramacion, $codigoProgramacionDetalle, $usuario)
    {
        $em = $this->getEntityManager();
        $douNetoTotal = 0;
        $numeroPagos = 0;
        $arConceptoHora = $em->getRepository(RhuConceptoHora::class)->findAll();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->autorizarProgramacion();
        if ($arConfiguracion['codigoConceptoFondoSolidaridadPensionFk']) {
            $arConceptoFondoSolidaridadPension = $em->getRepository(RhuConcepto::class)->find($arConfiguracion['codigoConceptoFondoSolidaridadPensionFk']);
            if ($codigoProgramacionDetalle) {
                $arProgramacionDetalleActualizar = $em->getRepository(RhuProgramacionDetalle::class)->find($codigoProgramacionDetalle);
                $vrNeto = $em->getRepository(RhuPago::class)->generar($arProgramacionDetalleActualizar, $arProgramacion, $arConceptoHora, $arConfiguracion, $arConceptoFondoSolidaridadPension, $usuario);
                $arProgramacionDetalleActualizar->setVrNeto($vrNeto);
                $em->persist($arProgramacionDetalleActualizar);
                $arProgramacion->setVrNeto($arProgramacion->getVrNeto() + $vrNeto);
                $em->persist($arProgramacion);
                $em->flush();
            } else {
                $arProgramacionDetalles = $em->getRepository(RhuProgramacionDetalle::class)->findBy(['codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()]);
                if ($arProgramacionDetalles) {
                    foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                        $vrNeto = $em->getRepository(RhuPago::class)->generar($arProgramacionDetalle, $arProgramacion, $arConceptoHora, $arConfiguracion, $arConceptoFondoSolidaridadPension, $usuario);
                        $arProgramacionDetalle->setVrNeto($vrNeto);
                        $em->persist($arProgramacionDetalle);
                        $douNetoTotal += $vrNeto;
                        $numeroPagos++;
                    }
                    $arProgramacion->setEstadoAutorizado(1);
                    $arProgramacion->setVrNeto($douNetoTotal);
                    $em->persist($arProgramacion);
                    $em->flush();
                }
            }
        } else {
            Mensajes::error("No esta configurado el concepto de fondo de solidaridad, debe configurarlo para autorizar el documento");
        }

    }

    public function cargarContratosTurnos($codigoSoporte, $arProgramacion)
    {
        $em = $this->getEntityManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);

        $arSoporte = $em->getRepository(TurSoporte::class)->find($codigoSoporte);
        if($arSoporte->getEstadoAprobado()) {
            //if ($arSoportePagoPeriodo->getEstadoAprobadoPagoNomina() == 1) {
            //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->eliminarProgramacionPago($codigoProgramacionPago);
            $arrInconsistencias = array();
            $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->cargarNomina($codigoSoporte);
            foreach ($arSoportesContratos as $arSoporteContrato) {

                $salario = $arSoporteContrato['vrSalario'];
                /*if ($arSoportePago->getVrSalarioCompensacion() > 0) {
                    $salario = $arSoportePago->getVrSalarioCompensacion();
                }*/
                $vrDia = $salario / 30;
                $vrHora = $vrDia / $arSoporteContrato['factorHorasDia'];
                $arProgramacionDetalle = new RhuProgramacionDetalle();
                $arProgramacionDetalle->setEmpleadoRel($em->getReference(RhuEmpleado::class, $arSoporteContrato['codigoEmpleadoFk']));
                $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                $arProgramacionDetalle->setContratoRel($em->getReference(RhuContrato::class, $arSoporteContrato['codigoContratoFk']));
                $arProgramacionDetalle->setVrSalario($salario);
                //$arProgramacionDetalle->setSoporteTurno(TRUE);
                //$arProgramacionDetalle->setCodigoSoportePagoFk($arSoportePago->getCodigoSoportePagoPk());
                $arProgramacionDetalle->setFechaDesde($arSoporteContrato['fechaDesde']);
                $arProgramacionDetalle->setFechaHasta($arSoporteContrato['fechaHasta']);
                //$arProgramacionDetalle->setCodigoCompensacionTipoFk($arSoporteContrato->getCodigoCompensacionTipoFk());
                //$arProgramacionDetalle->setCodigoSalarioFijoFk($arSoporteContrato->getCodigoSalarioFijoFk());
                //$arProgramacionDetalle->setSalarioBasico($arSoportePago->getSalarioBasico());
                if ($arSoporteContrato['contratoFechaDesde'] < $arProgramacion->getFechaDesde()) {
                    $arProgramacionDetalle->setFechaDesdeContrato($arSoporteContrato['fechaDesde']);
                } else {
                    $arProgramacionDetalle->setFechaDesdeContrato($arSoporteContrato['contratoFechaDesde']);
                }
                $arProgramacionDetalle->setFechaHastaContrato($arSoporteContrato['fechaHasta']);
                $intDias = $arSoporteContrato['dias'];
                $intDiasTransporte = $arSoporteContrato['diasTransporte'];
                $arProgramacionDetalle->setDias($intDias);
                //$arProgramacionDetalle->setDiasReales($intDias);
                $arProgramacionDetalle->setDiasTransporte($intDiasTransporte);
                //$arProgramacionDetalle->setFactorDia($arContrato->getFactorHorasDia());
                $arProgramacionDetalle->setVrDia($vrDia);
                $arProgramacionDetalle->setVrHora($vrHora);
                //Tiempo adicional
                $horasNovedad = $arSoporteContrato['novedad'] * 8;
                $intHoras = $arSoporteContrato['horasDescanso'] + $arSoporteContrato['horasDiurnas'] + $arSoporteContrato['horasNocturnas'] + $arSoporteContrato['horasFestivasDiurnas'] + $arSoporteContrato['horasFestivasNocturnas'];
                $intHorasReales = $intHoras + $horasNovedad;
                //$arProgramacionDetalle->setHorasPeriodo($intHoras);
                //$arProgramacionDetalle->setHorasPeriodoReales($intHorasReales);
                //$arProgramacionDetalle->setHorasNovedad($horasNovedad);
                $arProgramacionDetalle->setHorasDescanso($arSoporteContrato['horasDescanso']);
                $arProgramacionDetalle->setHorasDiurnas($arSoporteContrato['horasDiurnas']);
                //$arProgramacionDetalle->setHorasAdicionales($arSoporteContrato->getHorasAdicionales());
                //$arProgramacionDetalle->setHorasDomingo($arSoporteContrato->getHorasDomingo());
                $arProgramacionDetalle->setHorasNocturnas($arSoporteContrato['horasNocturnas']);
                $arProgramacionDetalle->setHorasFestivasDiurnas($arSoporteContrato['horasFestivasDiurnas']);
                $arProgramacionDetalle->setHorasFestivasNocturnas($arSoporteContrato['horasFestivasNocturnas']);
                $arProgramacionDetalle->setHorasExtrasOrdinariasDiurnas($arSoporteContrato['horasExtrasOrdinariasDiurnas']);
                $arProgramacionDetalle->setHorasExtrasOrdinariasNocturnas($arSoporteContrato['horasExtrasOrdinariasNocturnas']);
                $arProgramacionDetalle->setHorasExtrasFestivasDiurnas($arSoporteContrato['horasExtrasFestivasDiurnas']);
                $arProgramacionDetalle->setHorasExtrasFestivasNocturnas($arSoporteContrato['horasExtrasFestivasNocturnas']);
                $arProgramacionDetalle->setHorasRecargo($arSoporteContrato['horasRecargo']);
                $arProgramacionDetalle->setHorasRecargoNocturno($arSoporteContrato['horasRecargoNocturno']);
                $arProgramacionDetalle->setHorasRecargoFestivoDiurno($arSoporteContrato['horasRecargoFestivoDiurno']);
                $arProgramacionDetalle->setHorasRecargoFestivoNocturno($arSoporteContrato['horasRecargoFestivoNocturno']);


                //Pregunta por el tipo de pension, si es pensionado no le retiene pension (PABLO ARANZAZU 27/04/2016)
                /*if ($arContrato->getCodigoTipoPensionFk() == 5) {
                    $arProgramacionDetalle->setDescuentoPension(0);
                }

                //dias vacaciones
                $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->dias($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHastaReal());
                $intDiasVacaciones = $arrVacaciones['dias'];
                if ($intDiasVacaciones > 0) {
                    $arProgramacionDetalle->setDiasVacaciones($intDiasVacaciones);
                    $arProgramacionDetalle->setIbcVacaciones($arrVacaciones['ibc']);
                }

                //dias licencia
                $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicenciaPeriodo31($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHastaReal(), $arContrato->getCodigoEmpleadoFk());
                if ($intDiasLicencia > 0) {
                    $arProgramacionDetalle->setDiasLicencia($intDiasLicencia);
                }

                //dias incapacidad
                $intDiasIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidadPeriodo31($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHastaReal(), $arContrato->getCodigoEmpleadoFk());
                if ($intDiasIncapacidad > 0) {
                    $arProgramacionDetalle->setDiasIncapacidad($intDiasIncapacidad);
                }

                if ($intDiasVacaciones != $arSoportePago->getVacacion()) {
                    $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNumeroIdentificacion() . "-" . $arEmpleado->getNombreCorto() . " tiene vacaciones de " . $arSoportePago->getVacacion() . " dias en turnos y de " . $intDiasVacaciones . " en recurso humano");
                }
                $intDiasLicenciaSoportePago = $arSoportePago->getLicencia() + $arSoportePago->getLicenciaNoRemunerada() + $arSoportePago->getAusentismo();
                if ($intDiasLicencia != $intDiasLicenciaSoportePago) {
                    $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNumeroIdentificacion() . "-" . $arEmpleado->getNombreCorto() . " tiene licencias de " . $intDiasLicenciaSoportePago . " dias en turnos y de " . $intDiasLicencia . " en recurso humano");
                }

                if ($intDiasIncapacidad != $arSoportePago->getIncapacidad()) {
                    $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNumeroIdentificacion() . "-" . $arEmpleado->getNombreCorto() . " tiene incapacidades de " . $arSoportePago->getIncapacidad() . " dias en turnos y de " . $intDiasIncapacidad . " en recurso humano");
                }
                if ($arSoportePagoPeriodo->getAjusteDevengado()) {
                    if ($arSoportePago->getVrAjusteDevengadoPactado() > 0) {
                        $arProgramacionDetalle->setVrAjusteDevengado($arSoportePago->getVrAjusteDevengadoPactado());
                    }
                }
                if ($arSoportePago->getVrDevengadoPactadoCompensacion() > 0) {
                    $arProgramacionDetalle->setVrDevengadoPactadoCompensacion($arSoportePago->getVrDevengadoPactadoCompensacion());
                }
                if ($arSoportePago->getVrAjusteCompensacion() > 0) {
                    $arProgramacionDetalle->setVrAjusteDevengado($arSoportePago->getVrAjusteCompensacion());
                }
                if ($arSoportePago->getVrRecargoCompensacion() > 0) {
                    $arProgramacionDetalle->setVrAjusteRecargo($arSoportePago->getVrRecargoCompensacion());
                }
                if ($arSoportePago->getVrComplementarioCompensacion() > 0) {
                    $arProgramacionDetalle->setVrAjusteComplementario($arSoportePago->getVrComplementarioCompensacion());
                }*/
                $em->persist($arProgramacionDetalle);
            }

            /*$arProgramacionPago->setInconsistencias(0);
            if (count($arrInconsistencias) > 0) {
                $arProgramacionPago->setInconsistencias(1);
                foreach ($arrInconsistencias as $arrInconsistencia) {
                    $arProgramacionPagoInconsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia();
                    $arProgramacionPagoInconsistencia->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoInconsistencia->setInconsistencia($arrInconsistencia['inconsistencia']);
                    $em->persist($arProgramacionPagoInconsistencia);
                }
            }
            $arProgramacionPago->setEmpleadosGenerados(1);
            $arProgramacionPago->setNumeroEmpleados(count($arSoportesPago));
            $arProgramacionPago->setCodigoSoportePagoPeriodoFk($codigoSoportePagoPeriodo);
            */
            $em->persist($arProgramacion);
            //$arSoportePagoPeriodo->setEstadoBloqueoNomina(1);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            //}
        } else {
            Mensajes::error("El soporte no estaba aprobado");
        }
    }

}
