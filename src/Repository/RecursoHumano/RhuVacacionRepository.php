<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionCuenta;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class RhuVacacionRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta()
    {
        return 'recursohumano_movimiento_nomina_vacacion_';
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuVacacion::class);
    }

    /**
     * @param $arVacacion RhuVacacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arVacacion)
    {
        $em = $this->getEntityManager();
        if (!$arVacacion->getEstadoAutorizado()) {
            $arVacacion->setEstadoAutorizado(1);
            $em->persist($arVacacion);
            $em->flush();

        } else {
            Mensajes::error('El despacho ya esta autorizado');
        }
    }

    /**
     * @param $arVacacion RhuVacacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arVacacion)
    {
        if ($arVacacion->getEstadoAutorizado() && !$arVacacion->getEstadoAprobado()) {
            $arVacacion->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arVacacion);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arVacacion RhuVacacion
     * @return string
     */
    public function aprobar($arVacacion, $usuario)
    {
        $em = $this->getEntityManager();
        if ($arVacacion->getEstadoAutorizado() == 1 && $arVacacion->getEstadoAprobado() == 0) {
            if($arVacacion->getVacacionTipoRel()->getCodigoConceptoDisfrutadaFk() && $arVacacion->getVacacionTipoRel()->getCodigoConceptoDineroFk()) {
                if($this->validarCreditoAprobacion($arVacacion->getCodigoVacacionPk())) {
                    $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->autorizarProgramacion();
                    $numero = $arVacacion->getNumero();
                    if($arVacacion->getNumero() == 0) {
                        $arVacacionTipo = $em->getRepository(RhuVacacionTipo::class)->find($arVacacion->getCodigoVacacionTipoFk());
                        $arVacacion->setNumero($arVacacionTipo->getConsecutivo());
                        $arVacacionTipo->setConsecutivo($arVacacionTipo->getConsecutivo() + 1);
                        $em->persist($arVacacionTipo);
                        $numero = $arVacacion->getNumero();
                    }
                    $validar = "";
                    $arPagoTipo = $em->getRepository(RhuPagoTipo::class)->find('VAC');
                    $arPago = new RhuPago();
                    $arPago->setPagoTipoRel($arPagoTipo);
                    $arPago->setVacacionRel($arVacacion);
                    $arPago->setGrupoRel($arVacacion->getContratoRel()->getGrupoRel());
                    $arPago->setEmpleadoRel($arVacacion->getEmpleadoRel());
                    $arPago->setVrSalarioContrato($arVacacion->getContratoRel()->getVrSalario());
                    $arPago->setFechaDesde($arVacacion->getFechaDesdeDisfrute());
                    $arPago->setFechaHasta($arVacacion->getFechaDesdeDisfrute());
                    $arPago->setFechaDesdeContrato($arVacacion->getFechaDesdeDisfrute());
                    $arPago->setFechaHastaContrato($arVacacion->getFechaDesdeDisfrute());
                    $arPago->setContratoRel($arVacacion->getContratoRel());
                    $arPago->setEntidadPensionRel($arVacacion->getContratoRel()->getEntidadPensionRel());
                    $arPago->setEntidadSaludRel($arVacacion->getContratoRel()->getEntidadSaludRel());
                    $arPago->setUsuario($arVacacion->getUsuario());
                    $arPago->setComentario($arVacacion->getComentarios());
                    $arPago->setEstadoAutorizado(1);
                    $arPago->setEstadoAprobado(1);
                    $arPago->setNumero($numero);
                    $arPago->setUsuario($usuario);
                    $em->persist($arPago);
                    $devengado = 0;
                    $deduccion = 0;
                    $neto = 0;

                    //Estos van en el detalle del pago
                    if ($arVacacion->getVrDisfrute() > 0) {
                        $arConceptoVacacion = $arVacacion->getVacacionTipoRel()->getConceptoDisfrutadaRel();
                        $arPagoDetalleVacacion = new RhuPagoDetalle();
                        $vrVacacionDisfrute = round($arVacacion->getVrDisfrute());
                        $arPagoDetalleVacacion->setPagoRel($arPago);
                        $arPagoDetalleVacacion->setConceptoRel($arConceptoVacacion);
                        $arPagoDetalleVacacion->setDetalle('');
                        $arPagoDetalleVacacion->setVrPago($vrVacacionDisfrute);
                        $arPagoDetalleVacacion->setOperacion($arConceptoVacacion->getOperacion());
                        $arPagoDetalleVacacion->setDias($arVacacion->getDiasDisfrutados());
                        $pagoOperado = $vrVacacionDisfrute * $arConceptoVacacion->getOperacion();
                        $arPagoDetalleVacacion->setVrPagoOperado($pagoOperado);
                        $em->persist($arPagoDetalleVacacion);
                        $neto += $pagoOperado;
                        $devengado += $vrVacacionDisfrute;
                    }

                    //Total de pago de vacacion por concepto de vacacion , se consulta el parametro en vacacionParametrizacion
                    if ($arVacacion->getVrDinero() > 0) {
                        $arPagoConceptoVacacion = $arVacacion->getVacacionTipoRel()->getConceptoDineroRel();
                        $arPagoDetalleVacacion = new RhuPagoDetalle();
                        $vrvacacionDinero = $arVacacion->getVrDinero();
                        $arPagoDetalleVacacion->setPagoRel($arPago);
                        $arPagoDetalleVacacion->setConceptoRel($arPagoConceptoVacacion);
                        $arPagoDetalleVacacion->setDetalle('');
                        $arPagoDetalleVacacion->setVrPago($vrvacacionDinero);
                        $arPagoDetalleVacacion->setOperacion($arPagoConceptoVacacion->getOperacion());
                        $arPagoDetalleVacacion->setDias($arVacacion->getDiasPagados());
                        $pagoOperado = $vrvacacionDinero * $arPagoConceptoVacacion->getOperacion();
                        $arPagoDetalleVacacion->setVrPagoOperado($pagoOperado);
                        $em->persist($arPagoDetalleVacacion);
                        $neto += $pagoOperado;
                        $devengado += $vrvacacionDinero;
                    }

                    $codigoPension = $arVacacion->getContratoRel()->getPensionRel()->getCodigoConceptoFk();
                    $porcentajePension = $arVacacion->getContratoRel()->getPensionRel()->getPorcentajeEmpleado();
                    if ($codigoPension) {
                        //Total de pago de vacacion por concepto de pension, se consulta el parametro en vacacionParametrizacion
                        $arPagoConceptoPension = $em->getRepository(RhuConcepto::class)->find($codigoPension);
                        $arPagoDetallePension = new RhuPagoDetalle();
                        $arPagoDetallePension->setPagoRel($arPago);
                        $arPagoDetallePension->setConceptoRel($arPagoConceptoPension);
                        $arPagoDetallePension->setDetalle('');
                        $arPagoDetallePension->setVrPago($arVacacion->getVrPension());
                        $arPagoDetallePension->setPorcentaje($porcentajePension);
                        $arPagoDetallePension->setOperacion($arPagoConceptoPension->getOperacion());
                        $pagoOperado = $arVacacion->getVrPension() * $arPagoConceptoPension->getOperacion();
                        $arPagoDetallePension->setVrPagoOperado($pagoOperado);
                        $em->persist($arPagoDetallePension);
                        $neto += $pagoOperado;
                        $deduccion += $arVacacion->getVrPension();
                    } else {
                        $validar = "El codigo de pension no esta configurado correctamente";
                    }

                    $codigoSalud = $arVacacion->getContratoRel()->getSaludRel()->getCodigoConceptoFk();
                    $porcentajeSalud = $arVacacion->getContratoRel()->getSaludRel()->getPorcentajeEmpleado();
                    if ($codigoSalud) {
                        $arPagoConceptoSalud = $em->getRepository(RhuConcepto::class)->find($codigoSalud);
                        $arPagoDetalleSalud = new RhuPagoDetalle();
                        $arPagoDetalleSalud->setPagoRel($arPago);
                        $arPagoDetalleSalud->setConceptoRel($arPagoConceptoSalud);
                        $arPagoDetalleSalud->setDetalle('');
                        $arPagoDetalleSalud->setVrPago($arVacacion->getVrSalud());
                        $arPagoDetalleSalud->setPorcentaje($porcentajeSalud);
                        $arPagoDetalleSalud->setOperacion($arPagoConceptoSalud->getOperacion());
                        $pagoOperado = $arVacacion->getVrSalud() * $arPagoConceptoSalud->getOperacion();
                        $arPagoDetalleSalud->setVrPagoOperado($pagoOperado);
                        $em->persist($arPagoDetalleSalud);
                        $neto += $pagoOperado;
                        $deduccion += $arVacacion->getVrSalud();
                    } else {
                        $validar = "El codigo de salud no esta configurado correctamente";
                    }

                    //Total de pago de vacacion por concepto de fondo de solidaridad pensional, se consulta el parametro en vacacionParametrizacion
                    if ($arVacacion->getVrFondoSolidaridad() > 0) {
                        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
                        $codigoSolidaridad = $arConfiguracion->getCodigoConceptoFondoSolidaridadPensionFk();
                        if ($codigoSolidaridad) {
                            $arPagoConceptoFondo = $em->getRepository(RhuConcepto::class)->find($codigoSolidaridad);
                            $arPagoDetalleFondoSolidaridad = new RhuPagoDetalle();
                            $arPagoDetalleFondoSolidaridad->setPagoRel($arPago);
                            $arPagoDetalleFondoSolidaridad->setConceptoRel($arPagoConceptoFondo);
                            $arPagoDetalleFondoSolidaridad->setDetalle('');
                            $arPagoDetalleFondoSolidaridad->setVrPago($arVacacion->getVrFondoSolidaridad());
                            $arPagoDetalleFondoSolidaridad->setPorcentaje($arPagoConceptoFondo->getPorPorcentaje());
                            $arPagoDetalleFondoSolidaridad->setOperacion($arPagoConceptoFondo->getOperacion());
                            $pagoOperado = $arVacacion->getVrFondoSolidaridad() * $arPagoConceptoFondo->getOperacion();
                            $arPagoDetalleFondoSolidaridad->setVrPagoOperado($pagoOperado);
                            $em->persist($arPagoDetalleFondoSolidaridad);
                            $neto += $pagoOperado;
                            $deduccion += $arVacacion->getVrFondoSolidaridad();
                        } else {
                            $validar = "El codigo de fondo solidaridad no esta configurado correctamente";
                        }
                    }

                    //Se recorren los adicionales de las vacaciones
                    $ingresoBaseCotizacionTotal = 0;
                    $ingresoBasePrestacionTotal = 0;
                    $ingresoBasePrestacionVacacionTotal = 0;
                    $arVacacionAdicionales = $em->getRepository(RhuVacacionAdicional::class)->findBy(array('codigoVacacionFk' => $arVacacion->getCodigoVacacionPk()));
                    foreach ($arVacacionAdicionales as $arVacacionAdicional) {
                        if ($arVacacionAdicional->getVrBonificacion() > 0) {
                            $vrPagoAdicional = $arVacacionAdicional->getVrBonificacion();
                            $devengado += $arVacacionAdicional->getVrBonificacion();
                        } else {
                            $vrPagoAdicional = $arVacacionAdicional->getVrDeduccion();
                            $deduccion += $arVacacionAdicional->getVrDeduccion();
                        }
                        $arPagoDetalle = new RhuPagoDetalle();
                        $arPagoDetalle->setPagoRel($arPago);
                        $arPagoDetalle->setConceptoRel($arVacacionAdicional->getConceptoRel());
                        $arPagoDetalle->setDetalle('');
                        $arPagoDetalle->setVrPago($vrPagoAdicional);
                        $arPagoDetalle->setOperacion($arVacacionAdicional->getConceptoRel()->getOperacion());
                        $pagoOperado = $vrPagoAdicional * $arVacacionAdicional->getConceptoRel()->getOperacion();
                        $arPagoDetalle->setVrPagoOperado($pagoOperado);
                        if ($arVacacionAdicional->getConceptoRel()->getGeneraIngresoBaseCotizacion()) {
                            $arPagoDetalle->setVrIngresoBaseCotizacion($vrPagoAdicional);
                            $ingresoBaseCotizacionTotal += $vrPagoAdicional;
                        }
                        if ($arVacacionAdicional->getConceptoRel()->getGeneraIngresoBasePrestacion()) {
                            $arPagoDetalle->setVrIngresoBasePrestacion($vrPagoAdicional);
                            $ingresoBasePrestacionTotal += $vrPagoAdicional;
                        }
                        if ($arVacacionAdicional->getConceptoRel()->getGeneraIngresoBasePrestacionVacacion()) {
                            $arPagoDetalle->setVrIngresoBasePrestacionVacacion($vrPagoAdicional);
                            $ingresoBasePrestacionVacacionTotal += $vrPagoAdicional;
                        }
                        $em->persist($arPagoDetalle);
                        $neto += $pagoOperado;
                        //Generar pago credito
                        if ($arVacacionAdicional->getCodigoCreditoFk() != null) {
                            $em->getRepository(RhuCreditoPago::class)->generar($arVacacionAdicional->getCodigoCreditoFk(),'VAC', $arVacacionAdicional->getVrDeduccion());
                        }

                        //Validar si algun adicional corresponde a un embargo para generar el pago en RhuEmbargoPago.
                        if ($arVacacionAdicional->getCodigoEmbargoFk() != "") {
                            $em->getRepository(RhuEmbargoPago::class)->generar($arVacacionAdicional->getCodigoEmbargoFk(), $arPago, $arVacacionAdicional->getVrDeduccion());
                        }
                    }
                    $arPago->setVrIngresoBaseCotizacion($ingresoBaseCotizacionTotal);
                    $arPago->setVrIngresoBasePrestacion($ingresoBasePrestacionTotal);
                    $arPago->setVrIngresoBasePrestacionVacacion($ingresoBasePrestacionVacacionTotal);
                    $arPago->setVrDevengado($devengado);
                    $arPago->setVrDeduccion($deduccion);
                    $arPago->setVrNeto($neto);
                    $em->getRepository(RhuPago::class)->liquidarProvision($arPago, $arConfiguracion);

                    if ($validar == '') {
                        $arVacacion->setFecha(new \DateTime('now'));
                        $arVacacion->setEstadoAprobado(1);
                        $arVacacion->setFecha($arVacacion->getFechaDesdeDisfrute());
                        $em->persist($arVacacion);
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arVacacion->getCodigoContratoFk());
                        $arContrato->setFechaUltimoPagoVacaciones($arVacacion->getFechaHastaPeriodo());
                        $em->persist($arContrato);
                        $em->flush();
                        $em->getRepository(RhuPago::class)->liquidar($arPago);
                        $em->flush();
                        $em->getRepository(RhuPago::class)->generarCuentaPagar($arPago);
                        $em->flush();
                    } else {
                        Mensajes::error($validar);
                    }
                }
            } else {
                Mensajes::error("En el tipo de vacacion no esta configurado el concepto de nomina para el valor pagado de la vacacion o el valor pagado en dinero");
            }
        } else {
            Mensajes::error("El documento debe estar autorizado y no puede estar previamente aprobado");
        }
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $em = $this->getEntityManager();
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoVacacion = null;
        $numero = null;
        $grupo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        $estadoContabilizado = null;

        if ($filtros) {
            $codigoVacacion = $filtros['codigoVacacion'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $grupo = $filtros['grupo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
            $estadoContabilizado = $filtros['estadoContabilizado'] ?? null;
        }

        $queryBuilder = $em->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->select('v.codigoVacacionPk')
            ->addselect('v.fecha')
            ->addSelect('v.numero')
            ->addselect('g.nombre as grupo')
            ->addselect('e.numeroIdentificacion as numeroIdentificacion')
            ->addselect('e.nombreCorto as empleado')
            ->addselect('v.fechaDesdePeriodo')
            ->addselect('v.fechaHastaPeriodo')
            ->addselect('v.fechaDesdeDisfrute')
            ->addselect('v.fechaHastaDisfrute')
            ->addselect('v.fechaInicioLabor')
            ->addselect('v.diasPagados')
            ->addselect('v.diasDisfrutados')
            ->addselect('v.diasDisfrutadosReales')
            ->addselect('v.vrTotal')
            ->addSelect('v.vrValor')
            ->addselect('v.estadoAutorizado')
            ->addselect('v.estadoAprobado')
            ->addselect('v.estadoAnulado')
            ->addSelect('v.estadoContabilizado')
            ->leftJoin('v.grupoRel', 'g')
            ->leftJoin('v.empleadoRel', 'e');
        if ($codigoVacacion) {
            $queryBuilder->andWhere("v.codigoVacacionPk = '{$codigoVacacion}'");
        }
        if ($grupo) {
            $queryBuilder->andWhere("v.codigoGrupoFk = '{$grupo}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("v.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("v.numero = '{$numero}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("v.fechaDesdeDisfrute >= '{$fechaDesde}'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("v.fechaDesdeDisfrute <= '{$fechaHasta}'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAnulado = 1");
                break;
        }
        switch ($estadoContabilizado) {
            case '0':
                $queryBuilder->andWhere("v.estadoContabilizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoContabilizado = 1");
                break;
        }
        $queryBuilder->orderBy('v.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('v.codigoVacacionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function diasProgramacion($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->select('v.codigoVacacionPk')
            ->addSelect('v.fechaDesdeDisfrute')
            ->addSelect('v.fechaHastaDisfrute')
            ->andWhere("(((v.fechaDesdeDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$fechaDesde' AND v.fechaDesdeDisfrute <= '$fechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$fechaHasta' AND v.fechaDesdeDisfrute <= '$fechaDesde')) "
                . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.diasDisfrutados > 0 AND v.estadoAnulado = 0");
        if ($codigoContrato) {
            $query->andWhere("v.codigoContratoFk = " . $codigoContrato);
        }
        $arVacaciones = $query->getQuery()->getResult();
        $intDiasDevolver = 0;
        $vrIbc = 0;
        foreach ($arVacaciones as $arVacacion) {
            $intDias = 0;
            $dateFechaDesde = date_create($fechaDesde);
            $dateFechaHasta = date_create($fechaHasta);
            if ($arVacacion['fechaDesdeDisfrute'] < $dateFechaDesde) {
                $dateFechaDesde = $dateFechaDesde;
            } else {
                $dateFechaDesde = $arVacacion['fechaDesdeDisfrute'];
            }

            if ($arVacacion['fechaHastaDisfrute'] > $dateFechaHasta) {
                $dateFechaHasta = $dateFechaHasta;
            } else {
                $dateFechaHasta = $arVacacion['fechaHastaDisfrute'];
            }
            if ($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDias = $intDias + 1;
                $intDiasDevolver += $intDias;
            }
            $vrIbc += 0;
            //$vrIbc += $intDias * $arVacacion->getVrIbcPromedio();
        }
        $arrDevolver = array('dias' => $intDiasDevolver, 'ibc' => $vrIbc);
        return $arrDevolver;

    }

    public function validarVacacion($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $boolValidar = TRUE;

        $qb = $this->getEntityManager()->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->select("count(v.codigoVacacionPk) AS vacaciones")
            ->where("v.fechaDesdeDisfrute <= '{$strFechaHasta}' AND  v.fechaHastaDisfrute >= '{$strFechaHasta}'")
            ->orWhere("v.fechaDesdeDisfrute <= '{$strFechaDesde}' AND  v.fechaHastaDisfrute >='{$strFechaDesde}' AND v.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->orWhere("v.fechaDesdeDisfrute >= '{$strFechaDesde}' AND  v.fechaHastaDisfrute <='{$strFechaHasta}' AND v.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->andWhere("v.codigoEmpleadoFk = '{$codigoEmpleado}' AND v.estadoAnulado = 0 ");
        $r = $qb->getQuery();
        $arrVacaciones = $r->getResult();
        if ($arrVacaciones[0]['vacaciones'] > 0) {
            $boolValidar = FALSE;
        }

        return $boolValidar;
    }

    public function liquidar($codigoVacacion)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        /** @var  $arVacacion RhuVacacion */
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);
        $arContrato = $arVacacion->getContratoRel();

        // Calcular fecha desde periodo y hasta periodo
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
        if ($fechaDesdePeriodo == null) {
            $fechaDesdePeriodo = $arContrato->getFechaDesde();
        }
        $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;
        $fechaHastaPeriodo = $em->getRepository(RhuLiquidacion::class)->diasPrestacionesHasta($intDias + 1, $fechaDesdePeriodo);
        $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
        $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);
        // Calcular fecha desde periodo y hasta periodo

        //Fecha ultimo anio
        $fechaDesdeUltimoAnio = date_create($arVacacion->getFechaDesdeDisfrute()->format('Y-m-d'));
        $fechaHastaUltimoAnio = date_create($arVacacion->getFechaDesdeDisfrute()->format('Y-m-d'));
        date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
        if ($fechaDesdeUltimoAnio < $arVacacion->getFechaDesdePeriodo()) {
            $fechaDesdeUltimoAnio = $arVacacion->getFechaDesdePeriodo();
        }

        //360 dias para que de 12 meses
        $objFunciones = new FuncionesController();
        $diasPeriodo = $objFunciones->diasPrestaciones($fechaDesdePeriodo, $arVacacion->getFechaHastaPeriodo());

//        Se comenta esta linea debido a que nuestros pagos no almacenan los dias de ausentismo
        $diasAusentismo = $em->getRepository(RhuPago::class)->diasAusentismo($fechaDesdePeriodo, $arVacacion->getFechaHastaPeriodo(), $arVacacion->getContratoRel()->getCodigoContratoPk());
        if ($arVacacion->getDiasAusentismoPropuesto() > 0) {
            $diasAusentismo = $arVacacion->getDiasAusentismoPropuesto();
        }

        //Para que la fecha hasta quede del 01/01/2016 al 01/01/2017 va dar 361 dias pero le restamos 1 para
        //que queden solo 360 porque en soga no se puede liquidar mas de un periodo.

        if ($diasPeriodo > 360) {
            $diasPeriodo = 360;
        }
        $mesesPeriodo = $diasPeriodo / 30;
        $arVacacion->setDiasPeriodo($diasPeriodo);
        $arVacacion->setMesesPeriodo($mesesPeriodo);
        $intDias = $arVacacion->getDias();
        $floSalario = $arVacacion->getContratoRel()->getVrSalario();

        //Analizar cambios de salario
        $fecha = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime('-90 day', strtotime($fecha));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        $fechaDesdeCambioSalario = date_create_from_format('Y-m-d H:i', $nuevafecha . " 00:00");
        $floSalarioPromedio = 0;
        $floSalarioPromedioPagado = 0;
        $fechaDesdeRecargos = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime('-365 day', strtotime($fechaDesdeRecargos));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        $fechaDesde = date_create($nuevafecha);

        $fechaDesdeRecargos = $nuevafecha;
        $fechaHastaRecargos = $arVacacion->getFecha()->format('Y-m-d');
//        $interval = date_diff($fechaDesdePeriodo, $fechaHastaPeriodo);

        //Recargos nocturnos
        $recargosNocturnos = 0;

        //Ayuda: recargos nocturnos desde el ultimo pagado
        //Ultimo año hasta la ultima fecha de pago nomina
        if($arVacacion->getCodigoLiquidacionRecargosFk() == 'RN001') {
            $fechaHastaUltimoAnio = date_create($arContrato->getFechaUltimoPago()->format('Y-m-d'));
            $fechaDesdeUltimoAnio = date_create($arContrato->getFechaUltimoPago()->format('Y-m-d'));
            date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
            $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnosIbp($fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arContrato->getCodigoContratoPk());
        }
        //Ayuda: recargos nocturnos del periodo que se esta liquidando
        //Periodo
        if($arVacacion->getCodigoLiquidacionRecargosFk() == 'RN002') {
            $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnosIbp($fechaDesdePeriodo->format('Y-m-d'), $fechaHastaPeriodo->format('Y-m-d'), $arContrato->getCodigoContratoPk());
        }

        $recargosNocturnos = round($recargosNocturnos);

        $arVacacion->setVrRecargoNocturno($recargosNocturnos);
        $arVacacion->setVrRecargoNocturnoInicial($arContrato->getIbpRecargoNocturnoInicial());
        $recargosNocturnosTotal = $recargosNocturnos + $arContrato->getIbpRecargoNocturnoInicial();
        $promedioRecargosNocturnos = $recargosNocturnosTotal / $mesesPeriodo;
        $promedioRecargosNocturnos = round($promedioRecargosNocturnos);
//        if($arConfiguracion->getVacacionesRecargoNocturnoUltimoAnioEspecial()){
//            $promedioRecargosNocturnos = $promedioRecargosNocturnos * 25.92 / 100;
//        }
        $arVacacion->setVrPromedioRecargoNocturno($promedioRecargosNocturnos);

        if ($arContrato->getCodigoSalarioTipoFk() == 'FIJ') {
            $floSalarioPromedio = $arContrato->getVrSalario();
        } else {
            $floSalarioPromedio = $arContrato->getVrSalario() + $promedioRecargosNocturnos;
        }
        if ($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
            $floSalarioPromedio = $arVacacion->getVrSalarioPromedioPropuesto();
        }
        if ($arVacacion->getVrSalarioPromedioPropuestoPagado() > 0) {
            $floSalarioPromedioPagado = $arVacacion->getVrSalarioPromedioPropuestoPagado();
            $floSalarioPromedio = $floSalarioPromedioPagado;
        } else {
            $floSalarioPromedioPagado = $floSalarioPromedio;
        }
        $diasDisfrutadosReales = $arVacacion->getDiasDisfrutadosReales();
        $floTotalVacacionBrutoDisfrute = $floSalarioPromedio / 30 * $diasDisfrutadosReales;
        $floTotalVacacionBrutoPagados = 0;

        //Validar si son pagadas todas
        if ($arVacacion->getDiasDisfrutadosReales() > 1) {
            $floTotalVacacionBrutoPagados = $floSalarioPromedioPagado / 30 * $arVacacion->getDiasPagados();
        } else {
            if ($arVacacion->getVrSalarioPromedioPropuestoPagado() > 0) {
                $floTotalVacacionBrutoPagados = $arVacacion->getVrSalarioPromedioPropuestoPagado() / 30 * $arVacacion->getDiasPagados();
            } else {
                $floTotalVacacionBrutoPagados = $arContrato->getVrSalario() / 30 * $arVacacion->getDiasPagados();
            }
        }

        if ($arConfiguracion->getLiquidarVacacionesPromedioUltimoAnio()) {
            //Empresas segurcol que liquida con promedio ibp
            if ($arConfiguracion->getLiquidarPrestacionesSalarioSuplementario()) {
                $diasPeriodoSuplementario = $objFunciones->diasPrestaciones($fechaDesdeUltimoAnio, $fechaHastaUltimoAnio);
                if ($diasPeriodoSuplementario > 360) {
                    $diasPeriodoSuplementario = 360;
                }
                $diasPeriodoSuplementarioPagadas = $diasPeriodoSuplementario;
//                $diasPeriodoSuplementarioPagadas -= $diasAusentismo;
                $suplementario = $em->getRepository(RhuPagoDetalle::class)->ibpSuplementarioVacaciones(
                    $fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arVacacion->getContratoRel()->getCodigoContratoPk(), 1);
                $suplementarioPromedio = 0;
                if ($diasPeriodoSuplementario > 0) {
                    $suplementarioPromedio = ($suplementario / $diasPeriodoSuplementario) * 30;
                }
                $floSalarioPromedio = $arContrato->getVrSalario() + $suplementarioPromedio;
                if ($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
                    $floSalarioPromedio = $arVacacion->getVrSalarioPromedioPropuesto();
                }
                $floTotalVacacionBrutoDisfrute = $floSalarioPromedio / 30 * $diasDisfrutadosReales; // aca se calcula el valor de las disfrutadas

                if ($arVacacion->getDiasPagados() > 0) {
                    // se comentan las linea de suplementario por solicitud del cliente
                    // el correo fue enviado el dia miercoles 16 de enero , ASUNTO: solicitud de vacaciones , enviado por Yaneth del correo auxiliarnomina@segurcol.com

//                    $suplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibpSuplementario(
//                        $fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arVacacion->getContratoRel()->getCodigoContratoPk());
                    $suplementarioPromedio = 0;
//                    if ($diasPeriodoSuplementarioPagadas > 0) {
//                        $suplementarioPromedio = ($suplementario / $diasPeriodoSuplementarioPagadas) * 30;
//                    }
                    $floSalarioPromedioPagado = $arContrato->getVrSalario() + $suplementarioPromedio;
                    if ($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
                        $floSalarioPromedioPagado = $arVacacion->getVrSalarioPromedioPropuesto();
                    }
                    $floTotalVacacionBrutoPagados = ($floSalarioPromedioPagado / 30) * $arVacacion->getDiasPagados();
                } else {
                    $floTotalVacacionBrutoPagados = 0;
                }
            }
        }
        $floTotalVacacionBruto = $floTotalVacacionBrutoDisfrute + $floTotalVacacionBrutoPagados;

        if ($arContrato->getSalarioIntegral() == 0) {
            $basePrestaciones = $floTotalVacacionBrutoDisfrute;
        } else {
            $basePrestaciones = ($floTotalVacacionBrutoDisfrute * 70) / 100;
        }
        $baseSeguridadSocial = 0;
        if ($arConfiguracion->getVacacionesBaseDescuentoLeyIbcMesAnterior()) {
            $arrIbc = $em->getRepository(RhuAporte::class)->ibcMesAnterior($arVacacion->getFechaDesdeDisfrute()->format('Y'), $arVacacion->getFechaDesdeDisfrute()->format('m'), $arVacacion->getCodigoEmpleadoFk());
            if ($arrIbc['respuesta'] == true) {
                $vrDiaMesAnterior = $arrIbc['ibc'] / $arrIbc['dias'];
                $baseSeguridadSocial = $vrDiaMesAnterior * $arVacacion->getDiasDisfrutadosReales();
            }
        } else {
            $baseSeguridadSocial = $floTotalVacacionBrutoDisfrute;
            if ($arContrato->getSalarioIntegral() == 1) {
                $baseSeguridadSocial = ($floTotalVacacionBrutoDisfrute * 70) / 100;
            }
        }


        $porcentajeSalud = $arContrato->getSaludRel()->getPorcentajeEmpleado();
        $douSalud = ($baseSeguridadSocial * $porcentajeSalud) / 100;
        if ($arVacacion->getVrSaludPropuesto() > 0) {
            $douSalud = $arVacacion->getVrSaludPropuesto();
        }
        $douSalud = round($douSalud);
        $arVacacion->setVrSalud($douSalud);
        $porcentajePension = $arContrato->getPensionRel()->getPorcentajeEmpleado();

        $douPension = ($baseSeguridadSocial * $porcentajePension) / 100;
        if ($arVacacion->getVrPensionPropuesto() > 0) {
            $douPension = $arVacacion->getVrPensionPropuesto();
        }
        $douPension = round($douPension);
        $arVacacion->setVrPension($douPension);

        //Calcular el valor de fondo de solidaridad pensional.
        $duoFondo = 0;
        if ($basePrestaciones >= ($arConfiguracion->getVrSalarioMinimo() * 4)) {
            $douPorcentajeFondo = $em->getRepository(RhuAporteDetalle::class)->porcentajeFondo($arConfiguracion->getVrSalarioMinimo(), $basePrestaciones);
            $duoFondo = ($baseSeguridadSocial * $douPorcentajeFondo) / 100;
        }
        $duoFondo = round($duoFondo);
        $arVacacion->setVrFondoSolidaridad($duoFondo);

        $floDeducciones = 0;
        $floBonificaciones = 0;
        //Liquidar los embargos si el empleado tiene embargos
        $this->liquidarEmbargos($arVacacion);
        $arVacacionAdicionales = $em->getRepository(RhuVacacionAdicional::class)->FindBy(array('codigoVacacionFk' => $codigoVacacion));
        foreach ($arVacacionAdicionales as $arVacacionAdicional) {
            if ($arVacacionAdicional->getVrDeduccion() > 0) {
                $floDeducciones += $arVacacionAdicional->getVrDeduccion();
            }
            if ($arVacacionAdicional->getVrBonificacion() > 0) {
                $floBonificaciones += $arVacacionAdicional->getVrBonificacion();
            }
        }
        $floBonificaciones = round($floBonificaciones);
        $floDeducciones = round($floDeducciones);
        $floSalario = round($floSalario);
        $floSalarioPromedio = round($floSalarioPromedio);
        $floTotalVacacionBruto = round($floTotalVacacionBruto);
        $promedioIbc = $floTotalVacacionBruto / $arVacacion->getDias();
        $promedioIbc = round($promedioIbc);
        $arVacacion->setVrIbcPromedio($promedioIbc);
        $arVacacion->setVrBonificacion($floBonificaciones);
        $arVacacion->setVrDeduccion($floDeducciones);

        if ($arVacacion->getVrDisfrutePropuesto() > 0) {
            $floTotalVacacionBruto = $arVacacion->getVrDisfrutePropuesto();
            $floTotalVacacionBrutoDisfrute = $arVacacion->getVrDisfrutePropuesto();
            $floTotalVacacionBrutoPagados = 0;
            $arVacacion->setVrSalud($arVacacion->getVrDisfrutePropuesto() * 0.04);
            $arVacacion->setVrPension($arVacacion->getVrDisfrutePropuesto() * 0.04);
            $floSalarioPromedio = $floSalario;
        }
        $arVacacion->setVrBruto($floTotalVacacionBruto);
        $arVacacion->setVrDisfrute($floTotalVacacionBrutoDisfrute);
        $arVacacion->setVrDinero($floTotalVacacionBrutoPagados);
        $floTotalVacacion = ($floTotalVacacionBruto + $floBonificaciones) - $floDeducciones - $arVacacion->getVrPension() - $arVacacion->getVrSalud() - $arVacacion->getVrFondoSolidaridad();
        $floTotalVacacion = round($floTotalVacacion);
        $arVacacion->setVrTotal($floTotalVacacion);
        $arVacacion->setVrSalarioActual($floSalario);
        $arVacacion->setVrSalarioPromedio($floSalarioPromedio);
        $arVacacion->setDiasAusentismo($diasAusentismo);
        $arVacacion->setEstadoLiquidado(true);
        $em->persist($arVacacion);
        $em->flush();
        return true;
    }

    public function liquidarEmbargos($arVacacion)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $douVrSalarioMinimo = $arConfiguracion->getVrSalarioMinimo();
        $em->getRepository(RhuVacacionAdicional::class)->eliminarEmbargosVacacion($arVacacion->getCodigoVacacionPk());
        /** @var  $arEmbargo RhuEmbargo */
        $arEmbargos = $em->getRepository(RhuEmbargo::class)->listaEmbargo($arVacacion->getEmpleadoRel()->getCodigoEmpleadoPk(), 0, 1, 0, 0);
        foreach ($arEmbargos as $arEmbargo) {
            $vrDeduccionEmbargo = 0;
            $detalle = "";
            $vrPago = $arVacacion->getVrBruto();
            //Calcular la deduccion del embargo
            if ($arEmbargo->getValorFijo()) {
                $vrDeduccionEmbargo = $arEmbargo->getVrValor();
                $detalle = "Valor fijo embargo";
            }
            if ($arEmbargo->getPorcentajeDevengado() || $arEmbargo->getPorcentajeDevengadoPrestacional()) {
                $vrDeduccionEmbargo = ($vrPago * $arEmbargo->getPorcentaje()) / 100;
                $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                $detalle = $arEmbargo->getPorcentaje() . "% devengado";
            }
            $boolPorcentajeDescuentoLey = $arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLey() || $arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte() || $arEmbargo->getPorcentajeDevengadoMenosDescuentoLey() || $arEmbargo->getPorcentajeDevengadoMenosDescuentoLeyTransporte();
            if ($boolPorcentajeDescuentoLey) {
                $vrDeduccionEmbargo = (($vrPago - $arVacacion->getVrSalud() - $arVacacion->getVrPension()) * $arEmbargo->getPorcentaje()) / 100;
                $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                $detalle = $arEmbargo->getPorcentaje() . "% devengado (menos dcto ley)";
            }
            if ($arEmbargo->getPorcentajeExcedaSalarioMinimo()) {
                $salarioMinimoDevengado = ($douVrSalarioMinimo / 30) * $arVacacion->getDias();
                $baseDescuento = $vrPago - $salarioMinimoDevengado;
                if ($baseDescuento > 0) {
                    $vrDeduccionEmbargo = ($baseDescuento * $arEmbargo->getPorcentaje()) / 100;
                    $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                }
                $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
            }
            if ($arEmbargo->getPorcentajeSalarioMinimo()) {
                if ($douVrSalarioMinimo > 0) {
                    $vrDeduccionEmbargo = ($douVrSalarioMinimo * $arEmbargo->getPorcentaje()) / 100;
                    $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                    $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
                }
            }
            if ($arEmbargo->getPartesExcedaSalarioMinimo()) {
                $salarioMinimoDevengado = ($douVrSalarioMinimo / 30) * $arVacacion->getDias();
                $baseDescuento = $vrPago - $salarioMinimoDevengado;
                if ($baseDescuento > 0) {
                    $vrDeduccionEmbargo = $baseDescuento / $arEmbargo->getPartes();
                }
                $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
            }
            if ($arEmbargo->getPartesExcedaSalarioMinimoMenosDescuentoLey()) {
                $salarioMinimoDevengado = ($douVrSalarioMinimo / 30) * $arVacacion->getDias();
                $baseDescuento = ($vrPago - $arVacacion->getVrSalud() - $arVacacion->getVrPension()) - $salarioMinimoDevengado;
                if ($baseDescuento > 0) {
                    $vrDeduccionEmbargo = $baseDescuento / $arEmbargo->getPartes();
                }
                $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
            }
            if ($arEmbargo->getValidarMontoMaximo()) {
                $saldo = $arEmbargo->getVrMontoMaximo() - $arEmbargo->getDescuento();
                if ($saldo > 0) {
                    if ($saldo < $vrDeduccionEmbargo) {
                        $vrDeduccionEmbargo = round($saldo);
                    }
                } else {
                    $vrDeduccionEmbargo = 0;
                }
            }
            //Aqui se registra la deduccion del embargo en la liquidacion adicionales
            if ($vrDeduccionEmbargo > 0) {
                $arVacacionAdicional = new RhuVacacionAdicional();
                $arVacacionAdicional->setConceptoRel($arEmbargo->getEmbargoTipoRel()->getConceptoRel());
                $arVacacionAdicional->setEmbargoRel($arEmbargo);
                $arVacacionAdicional->setVacacionRel($arVacacion);
                $arVacacionAdicional->setVrDeduccion($vrDeduccionEmbargo);
                $arVacacionAdicional->setDetalle($detalle);
                $em->persist($arVacacionAdicional);
            }
        }
    }

    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "")
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $queryBuilder = $em->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->addSelect('v.codigoVacacionPk')
            ->addSelect('v.diasDisfrutadosReales')
            ->addSelect('v.fechaDesdeDisfrute')
            ->addSelect('v.fechaHastaDisfrute')
            ->addSelect('v.vrIbcPromedio')
            ->where("v.estadoAnulado = 0 AND (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) ");

        /*$dql = "SELECT vacacion FROM BrasaRecursoHumanoBundle:RhuVacacion vacacion "
            . "WHERE vacacion.estadoAnulado = 0 AND (((vacacion.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (vacacion.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (vacacion.fechaDesdeDisfrute >= '$strFechaDesde' AND vacacion.fechaDesdeDisfrute <= '$strFechaHasta') "
            . "OR (vacacion.fechaHastaDisfrute >= '$strFechaHasta' AND vacacion.fechaDesdeDisfrute <= '$strFechaDesde')) ";*/
        if ($codigoEmpleado != "") {
            $queryBuilder->andWhere("v.codigoEmpleadoFk = {$codigoEmpleado}");
        }
        $arVacaciones = $queryBuilder->getQuery()->getResult();
        return $arVacaciones;
    }

    public function diasValidarTurnos($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT v FROM App\Entity\RecursoHumano\RhuVacacion v "
            . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
            . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
            . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.diasDisfrutados > 0 AND v.estadoAnulado = 0";
        if ($codigoContrato != "") {
            $dql .= " AND v.codigoContratoFk = {$codigoContrato}";
        }

        $query = $em->createQuery($dql);
        $arVacaciones = $query->getResult();
        $intDiasDevolver = 0;
        $vrIbc = 0;
        foreach ($arVacaciones as $arVacacion) {
            $intDias = 0;
            $dateFechaDesde = "";
            $dateFechaHasta = "";
            if ($arVacacion->getFechaDesdeDisfrute() < $fechaDesde == true) {
                $dateFechaDesde = $fechaDesde;
            } else {
                $dateFechaDesde = $arVacacion->getFechaDesdeDisfrute();
            }

            if ($arVacacion->getFechaHastaDisfrute() > $fechaHasta == true) {
                $dateFechaHasta = $fechaHasta;
            } else {
                $dateFechaHasta = $arVacacion->getFechaHastaDisfrute();
            }
            if ($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDias = $intDias + 1;
                $intDiasDevolver += $intDias;
            }
            $vrIbc += $intDias * $arVacacion->getVrIbcPromedio();
        }
        $arrDevolver = array('dias' => $intDiasDevolver, 'ibc' => $vrIbc);
        return $arrDevolver;
    }

    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $arVacacion = $this->getEntityManager()->getRepository(RhuVacacion::class)->find($arrSeleccionado);
            try {
                if ($arVacacion) {
                    $this->getEntityManager()->remove($arVacacion);
                }
                $this->getEntityManager()->flush();
            } catch (\Exception $ex) {
                Mensajes::error("El registro tiene registros relacionados");
            }
        }
    }

    /**
     * @param $arr
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function contabilizar($arr): bool
    {
        /**
         * @var $arVacacion RhuVacacion
         */
        $em = $this->getEntityManager();
        if ($arr) {
            Mensajes::error("La contabilizacion de este documento es generada por el documento pago");
            /*$error = "";
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(7);
            $codigoCuentaPagadas = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(8);
            $codigoCuentaDisfrutadas = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(9);
            $codigoCuentaSalud = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(10);
            $codigoCuentaPension = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(11);
            $codigoCuentaFondo = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(12);
            $codigoCuentaVacacion = $arCuenta->getCodigoCuentaFk();
            $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
            $arComprobanteContable = $em->getRepository(FinComprobante::class)->find($arConfiguracion->getCodigoComprobanteVacacion());
            foreach ($arr AS $codigo) {
                $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigo);
                if ($arVacacion) {
                    if ($arVacacion->getEstadoAprobado() == 1 && $arVacacion->getEstadoContabilizado() == 0) {
                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arVacacion->getContratoRel()->getCentroCostoRel());
                        $arTercero = $em->getRepository(RhuEmpleado::class)->terceroFinanciero($arVacacion->getCodigoEmpleadoFk());
                        //Vacaciones
                        if ($arVacacion->getVrBruto() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaDisfrutadas);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arVacacion->getNumero());
                                $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                $arRegistro->setFecha($arVacacion->getFechaContabilidad());
                                $arRegistro->setVrDebito($arVacacion->getVrBruto());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setDescripcion('VACACIONES');
                                $arRegistro->setCodigoModeloFk('RhuVacacion');
                                $arRegistro->setCodigoDocumento($arVacacion->getCodigoVacacionPk());
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Pension
                        if ($arVacacion->getVrPension() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaPension);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setTerceroRel($arTercero);
                                // Cuando el detalle es de salud y pension se lleva al nit de la entidad
                                if ($arCuenta->getExigeTercero() == 1) {
                                    $arTerceroPension = $em->getRepository(FinTercero::class)->findOneBy(array('numeroIdentificacion' => $arVacacion->getContratoRel()->getEntidadPensionRel()->getNit()));
                                    if ($arTerceroPension) {
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                    } else {
                                        $error = "La entidad de pension " . $arVacacion->getContratoRel()->getEntidadPensionRel()->getNombre() . " de la vacacion " . $arVacacion->getNumero() . " no existe en contabilidad";
                                        break;
                                    }
                                }
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setNumero($arVacacion->getNumero());
                                $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                $arRegistro->setFecha($arVacacion->getFechaContabilidad());
                                $arRegistro->setVrCredito($arVacacion->getVrPension());
                                $arRegistro->setNaturaleza("C");
                                $arRegistro->setDescripcion('PENSION');
                                $arRegistro->setCodigoModeloFk('RhuVacacion');
                                $arRegistro->setCodigoDocumento($arVacacion->getCodigoVacacionPk());
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Salud
                        if ($arVacacion->getVrSalud() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaSalud);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                if ($arCuenta->getExigeTercero() == 1) {
                                    $arTerceroSalud = $em->getRepository(FinTercero::class)->findOneBy(array('numeroIdentificacion' => $arVacacion->getContratoRel()->getEntidadSaludRel()->getNit()));
                                    if ($arTerceroSalud) {
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                    } else {
                                        $error = "La entidad de salud " . $arVacacion->getContratoRel()->getEntidadSaludRel()->getNombre() . " de la vacacion " . $arVacacion->getNumero() . " no existe en contabilidad";
                                        break;
                                    }
                                }
                                $arRegistro->setNumero($arVacacion->getNumero());
                                $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                $arRegistro->setFecha($arVacacion->getFechaContabilidad());
                                $arRegistro->setVrCredito($arVacacion->getVrSalud());
                                $arRegistro->setNaturaleza("C");
                                $arRegistro->setDescripcion('SALUD');
                                $arRegistro->setCodigoModeloFk('RhuVacacion');
                                $arRegistro->setCodigoDocumento($arVacacion->getCodigoVacacionPk());
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //FONDO SOLIDARIDAD PENSIONAL
                        if ($arVacacion->getVrFondoSolidaridad() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaFondo);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                //$arRegistro->setCentroCostoRel($arCentroCosto);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                if ($arCuenta->getExigeNit() == 1) {
                                    $arTerceroFondo = $em->getRepository(FinTercero::class)->findOneBy(array('numeroIdentificacion' => $arVacacion->getContratoRel()->getEntidadPensionRel()->getNit()));
                                    if ($arTerceroFondo) {
                                        $arRegistro->setTerceroRel($arTerceroFondo);
                                    } else {
                                        $error = "La entidad de pension " . $arVacacion->getContratoRel()->getEntidadPensionRel()->getNombre() . " de la vacacion " . $arVacacion->getNumero() . " no existe en contabilidad";
                                        break;
                                    }
                                }
                                $arRegistro->setNumero($arVacacion->getNumero());
                                $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                $arRegistro->setFecha($arVacacion->getFechaContabilidad());
                                $arRegistro->setVrCredito($arVacacion->getVrFondoSolidaridad());
                                $arRegistro->setNaturaleza("C");
                                $arRegistro->setDescripcion('FONDO SOLIDARIDAD');
                                $arRegistro->setCodigoModeloFk('RhuVacacion');
                                $arRegistro->setCodigoDocumento($arVacacion->getCodigoVacacionPk());
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Vacaciones por pagar
                        if ($arVacacion->getVrTotal() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaVacacion);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arVacacion->getNumero());
                                $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                $arRegistro->setFecha($arVacacion->getFechaContabilidad());
                                $arRegistro->setVrCredito($arVacacion->getVrTotal());
                                $arRegistro->setNaturaleza("C");
                                $arRegistro->setDescripcion('VACACIONES POR PAGAR');
                                $arRegistro->setCodigoModeloFk('RhuVacacion');
                                $arRegistro->setCodigoDocumento($arVacacion->getCodigoVacacionPk());
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Adicionales
                        $arVacacionAdicionales = $em->getRepository(RhuVacacionAdicional::class)->findBy(array('codigoVacacionFk' => $codigo));
                        foreach ($arVacacionAdicionales as $arVacacionAdicional) {
                            $arConceptoCuenta = $em->getRepository(RhuConceptoCuenta::class)->findOneBy(array('codigoConceptoFk' => $arVacacionAdicional->getCodigoConceptoFk(), 'codigoCostoClaseFk' => $arVacacion->getContratoRel()->getCodigoCostoClaseFk()));
                            if ($arConceptoCuenta) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arConceptoCuenta->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFechaContabilidad());
                                    if ($arVacacionAdicional->getVrBonificacion() > 0) {
                                        $arRegistro->setVrDebito($arVacacionAdicional->getVrBonificacion());
                                        $arRegistro->setNaturaleza("D");
                                    } else {
                                        $arRegistro->setVrCredito($arVacacionAdicional->getVrDeduccion());
                                        $arRegistro->setNaturaleza("C");
                                    }
                                    //Para contabilizar al nit fijo el concepto
                                    //if ($arVacacionAdicional->getConceptoRel()->getNumeroIdentificacionTerceroContabilidad() != null) {
                                    //  $arTerceroConcepto = $em->getRepository(FinTercero::class)->findOneBy(array('numeroIdentificacion' => $arVacacionAdicional->getConceptoRel()->getNumeroIdentificacionTerceroContabilidad()));
                                    //$arRegistro->setTerceroRel($arTerceroConcepto);
                                    //}
                                    //if ($arCuenta->getCodigoTerceroFijoFk()) {
                                    //$arTerceroDetalle = $em->getRepository(FinTercero::class)->find($arCuenta->getCodigoTerceroFijoFk());
                                    //$arRegistro->setTerceroRel($arTerceroDetalle);
                                    //}

                                    $arRegistro->setDescripcion($arVacacionAdicional->getConceptoRel()->getNombre());
                                    $arRegistro->setCodigoModeloFk('RhuVacacion');
                                    $arRegistro->setCodigoDocumento($arVacacion->getCodigoVacacionPk());
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "La cuenta " . $arConceptoCuenta->getCodigoCuentaFk() . " no existe en el plan de cuentas";
                                    break;
                                }
                            } else {
                                $error = "El concepto adicional de la liquidacion " . $arVacacionAdicional->getConceptoRel()->getNombre() . " no tiene cuenta configurada";
                                break;
                            }
                        }

                        $arVacacionAct = $em->getRepository(RhuVacacion::class)->find($arVacacion->getCodigoVacacionPk());
                        $arVacacionAct->setEstadoContabilizado(1);
                        $em->persist($arVacacionAct);
                    } else {
                        $error = "La vacacion con codigo " . $codigo . " Se encuentra sin aprobar o ya esta contabilizada ";
                    }
                } else {
                    $error = "La vacacion codigo " . $codigo . " no existe";
                    break;
                }
            }
            if ($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }*/

        }
        return true;
    }

    /*
     * Verificar la influencia de traer en un array los campos especificos o instanciar todo el objeto
     */
    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->select('v.codigoVacacionPk')
            ->addSelect('v.codigoEmpleadoFk')
            ->addSelect('v.codigoContratoFk')
            ->addSelect('v.codigoGrupoFk')
            ->addSelect('v.fecha')
            ->addSelect('v.fechaContabilidad')
            ->addSelect('v.numero')
            ->addSelect('v.fechaDesdePeriodo')
            ->addSelect('v.fechaHastaPeriodo')
            ->addSelect('v.fechaDesdeDisfrute')
            ->addSelect('v.fechaHastaDisfrute')
            ->addSelect('v.fechaInicioLabor')
            ->addSelect('v.vrSalud')
            ->addSelect('v.vrPension')
            ->addSelect('v.vrFondoSolidaridad')
            ->addSelect('v.vrIbc')
            ->addSelect('v.vrDeduccion')
            ->addSelect('v.vrBonificacion')
            ->addSelect('v.vrValor')
            ->addSelect('v.vrDisfrute')
            ->addSelect('v.vrDinero')
            ->addSelect('v.vrBruto')
            ->addSelect('v.vrTotal')
            ->addSelect('v.estadoAprobado')
            ->addSelect('v.estadoContabilizado')
            ->leftJoin('v.empleadoRel', 'e')
            ->leftJoin('v.contratoRel', 'c')
            ->leftJoin('v.grupoRel', 'g')
            ->where('v.codigoVacacionPk = ' . $codigo);
        $arFactura = $queryBuilder->getQuery()->getSingleResult();
        return $arFactura;
    }

    public function listaVacacionesMes($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(RhuVacacion::class, "v")
            ->select("v.codigoVacacionPk, v.fechaDesdeDisfrute, v.fechaHastaDisfrute")
            ->where("v.fechaDesdeDisfrute <= '{$fechaHasta->format('Y-m-d')}' AND  v.fechaHastaDisfrute >= '{$fechaHasta->format('Y-m-d')}'")
            ->orWhere("v.fechaDesdeDisfrute <= '{$fechaDesde->format('Y-m-d')}' AND  v.fechaHastaDisfrute >='{$fechaDesde->format('Y-m-d')}' AND v.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->orWhere("v.fechaDesdeDisfrute >= '{$fechaDesde->format('Y-m-d')}' AND  v.fechaHastaDisfrute <='{$fechaHasta->format('Y-m-d')}' AND v.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->andWhere("v.codigoEmpleadoFk = '{$codigoEmpleado}' AND v.estadoAnulado = 0 ");

        $arrVacacionesEmpleado = $qb->getQuery()->execute();
        return $arrVacacionesEmpleado;
    }

    public function dias($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT v FROM App\Entity\RecursoHumano\RhuVacacion v "
            . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
            . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
            . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.diasDisfrutados > 0 AND v.estadoAnulado = 0";
        if ($codigoContrato != "") {
            $dql .= " AND v.codigoContratoFk = {$codigoContrato}";
        }

        $query = $em->createQuery($dql);
        $arVacaciones = $query->getResult();
        $intDiasDevolver = 0;
        $vrIbc = 0;
        foreach ($arVacaciones as $arVacacion) {
            $intDias = 0;
            $dateFechaDesde = "";
            $dateFechaHasta = "";
            if ($arVacacion->getFechaDesdeDisfrute() < $fechaDesde == true) {
                $dateFechaDesde = $fechaDesde;
            } else {
                $dateFechaDesde = $arVacacion->getFechaDesdeDisfrute();
            }

            if ($arVacacion->getFechaHastaDisfrute() > $fechaHasta == true) {
                $dateFechaHasta = $fechaHasta;
            } else {
                $dateFechaHasta = $arVacacion->getFechaHastaDisfrute();
            }
            if ($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDias = $intDias + 1;
                $intDiasDevolver += $intDias;
            }
            $vrIbc += $intDias * $arVacacion->getVrIbcPromedio();
        }
        $arrDevolver = array('dias' => $intDiasDevolver, 'ibc' => $vrIbc);
        return $arrDevolver;
    }

    private function validarCreditoAprobacion($id) {
        $em = $this->getEntityManager();
        $arrCreditos = $em->getRepository(RhuVacacionAdicional::class)->resumenCredito($id);
        foreach ($arrCreditos as $arrCredito) {
            $arCredito = $em->getRepository(RhuCredito::class)->find($arrCredito['codigoCreditoFk']);
            if ($arCredito->getVrSaldo() < $arrCredito['total']) {
                Mensajes::error("El credito " . $arrCredito['codigoCreditoFk'] . " tiene un saldo de " . $arCredito->getVrSaldo() . " y la deduccion de " . $arrCredito['total'] . " lo supera");
                return false;
                break;
            }
        }
        return true;
    }

    public function detallePromedio($codigoVacacion)
    {

        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);
        $arContrato = $arVacacion->getContratoRel();

        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
        if ($fechaDesdePeriodo == null) {
            $fechaDesdePeriodo = $arContrato->getFechaDesde();
        }
        $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;
        $fechaHastaPeriodo = $em->getRepository(RhuLiquidacion::class)->diasPrestacionesHasta($intDias + 1, $fechaDesdePeriodo);

//        // sabatino
//        if ($arContrato->getCodigoTiempoFk() == 3) {
//            $arrIbpSalario = $em->getRepository(RhuPago::class)->listaIbpSalario($arContrato->getFechaUltimoPagoVacaciones()->format('Y-m-d'), $arContrato->getFechaHasta()->format('Y-m-d'), $arVacacion->getCodigoContratoFk());
//            return $arrIbpSalario;
//        }

//        // suplementario
//        if ($arConfiguracion->getLiquidarVacacionesPromedioUltimoAnio()) {
//            //Fecha ultimo anio
//            $fechaHastaUltimoAnio = date_create($arVacacion->getFechaDesdeDisfrute()->format('Y-m-d'));
//            $fechaDesdeUltimoAnio = date_create($arVacacion->getFechaDesdeDisfrute()->format('Y-m-d'));
//            date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
//            if ($fechaDesdeUltimoAnio < $arVacacion->getFechaDesdePeriodo()) {
//                $fechaDesdeUltimoAnio = $arVacacion->getFechaDesdePeriodo();
//            }
//            $diasPeriodoSuplementario = $objFunciones->diasPrestaciones($fechaDesdeUltimoAnio, $fechaHastaUltimoAnio);
//            if ($diasPeriodoSuplementario > 360) {
//                $diasPeriodoSuplementario--;
//            }
//            if ($arConfiguracion->getLiquidarPrestacionesSalarioSuplementario()) {
//                $arrSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaIbpSuplementarioVacaciones($fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arContrato->getCodigoContratoPk());
//                //  $suplementarioAdicionalVacacion = $em->getRepository("BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales")->ibpSuplementarioVacaciones($arLiquidacion);
//                return $arrSuplementario;
//            }
//        }

//        Recargos nocturnos sobre el porcentaje de vacaciones que tiene el concepto
        if ($arConfiguracion->getVacacionesLiquidarRecargoNocturnoPorcentajeConcepto()) {
            if ($arConfiguracion->getVacacionesRecargoNocturnoUltimoAnio()) {
                $fechaHastaUltimoAnio = date_create($arContrato->getFechaUltimoPago()->format('Y-m-d'));
                $fechaDesdeUltimoAnio = date_create($arContrato->getFechaUltimoPago()->format('Y-m-d'));
                date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
                if ($fechaDesdeUltimoAnio < $arVacacion->getFechaDesdePeriodo()) {
                    $fechaDesdeUltimoAnio = $arVacacion->getFechaDesdePeriodo();
                }
                $arrRecargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->listaRecargosNocturnosIbp($fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arContrato->getCodigoContratoPk());
                return $arrRecargosNocturnos;
            } else {
                $arrRecargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->listaRecargosNocturnosIbp($fechaDesdePeriodo->format('Y-m-d'), $fechaHastaPeriodo->format('Y-m-d'), $arContrato->getCodigoContratoPk());
                return $arrRecargosNocturnos;
            }
        }

    }

}