<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuIncapacidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuIncapacidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuIncapacidad::class);
    }


    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoIncapacidad = null;
        $entidadSalud = null;
        $numeroEps = null;
        $grupo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        $incapacidadTipo = null;
        $estadoTranscripcion = null;
        $estadoLegalizado = null;

        if ($filtros) {
            $codigoIncapacidad = $filtros['codigoIncapacidad'] ?? null;
            $incapacidadTipo = $filtros['incapacidadTipo'] ?? null;
            $estadoTranscripcion = $filtros['estadoTranscripcion'] ?? null;
            $estadoLegalizado = $filtros['estadoLegalizado'] ?? null;
            $entidadSalud = $filtros['entidadSalud'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $numeroEps = $filtros['numeroEps'] ?? null;
            $grupo = $filtros['grupo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncapacidad::class, 'i')
            ->select('i.codigoIncapacidadPk')
            ->addSelect('i.numero as numeroEps')
            ->addSelect('es.nombre as entidad')
            ->addSelect('e.numeroIdentificacion as documento')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('it.nombre')
            ->addSelect('i.fechaDesde')
            ->addSelect('i.fechaHasta')
            ->addSelect('i.diasAcumulados')
            ->addSelect('i.diasEntidad')
            ->addSelect('i.diasEmpresa')
            ->addSelect('i.vrCobro')
            ->addSelect('i.vrIncapacidad')
            ->addSelect('i.cantidad')
            ->addSelect('i.diasCobro')
            ->addSelect('i.diasIbcMesAnterior')
            ->addSelect('i.vrIbcMesAnterior')
            ->addSelect('i.estadoCobrar')
            ->addSelect('i.pagarEmpleado')
            ->addSelect('i.estadoProrroga')
            ->addSelect('i.estadoTranscripcion')
            ->addSelect('i.estadoLegalizado')
            ->addSelect('i.codigoUsuario')
            ->leftJoin('i.incapacidadTipoRel', 'it')
            ->leftJoin('i.entidadSaludRel', 'es')
            ->leftJoin('i.empleadoRel', 'e')
            ->orderBy('i.codigoIncapacidadPk', 'DESC');
        if ($codigoIncapacidad) {
            $queryBuilder->andWhere("i.codigoIncapacidadPk = '{$codigoIncapacidad}'");
        }
        if ($grupo) {
            $queryBuilder->andWhere("i.codigoGrupoFk = '{$grupo}'");
        }
        if ($entidadSalud) {
            $queryBuilder->andWhere("i.codigoEntidadSaludFk = '{$entidadSalud}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("i.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($numeroEps) {
            $queryBuilder->andWhere("i.numeroEps = '{$numeroEps}'");
        }
        if ($incapacidadTipo) {
            $queryBuilder->andWhere("i.codigoIncapacidadTipoFk = '{$incapacidadTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("i.fechaDesde >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("i.fechaHasta <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAnulado = 1");
                break;
        }
        switch ($estadoLegalizado) {
            case '0':
                $queryBuilder->andWhere("i.estadoLegalizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoLegalizado = 1");
                break;
        }
        switch ($estadoTranscripcion) {
            case '0':
                $queryBuilder->andWhere("i.estadoTranscripcion = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoTranscripcion = 1");
                break;
        }

        $queryBuilder->addOrderBy('i.codigoIncapacidadPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function buscar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncapacidad::class, 'i');
        $queryBuilder
            ->select('i.codigoIncapacidadPk')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('es.nombre as entidad')
            ->addSelect('i.numero as numeroEps')
            ->addSelect('i.fechaDesde')
            ->addSelect('i.fechaHasta')
            ->leftJoin('i.entidadSaludRel', 'es')
            ->leftJoin('i.empleadoRel', 'e');

        if ($session->get('filtroRhuIncapacidadBuscarNombre') != null) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroRhuIncapacidadBuscarNombre')}%' ");
        }

        if ($session->get('filtroRhuIncapacidadBuscarCodigo') != null) {
            $queryBuilder->andWhere("i.codigoIncapacidadPk = '{$session->get('filtroRhuIncapacidadBuscarCodigo')}'");
        }

        if ($session->get('filtroRhuIncapacidadBuscarIdentificacion') != null) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroRhuIncapacidadBuscarIdentificacion')}'");
        }

        return $queryBuilder;
    }

    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoIncapacidad = ""): bool
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncapacidad::class, 'i');
        $dql = "SELECT incapacidad FROM App\Entity\RecursoHumano\RhuIncapacidad incapacidad "
            . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
            . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) "
            . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        if ($codigoIncapacidad != "") {
            $dql = $dql . "AND incapacidad.codigoIncapacidadPk <> " . $codigoIncapacidad;
        }
        $objQuery = $em->createQuery($dql);
        $arIncapacidades = $objQuery->getResult();

        if (count($arIncapacidades) > 0) {
            return FALSE;
        } else {
            return TRUE;
        }

    }

    /**
     * @param $arIncapacidad RhuIncapacidad
     * @param $arUsuario
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($arIncapacidad, $arUsuario)
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arEmpleado = $arIncapacidad->getEmpleadoRel();
        $arContrato = $arIncapacidad->getContratoRel();
        $anioActual = (new \DateTime('now'))->format("Y");
        $anioIncapacidad = $arIncapacidad->getFecha()->format("Y");
        $salarioMinimo = $arConfiguracion->getVrSalarioMinimo();
        $salario = $arConfiguracion->getVrSalarioMinimo();
        if ($arIncapacidad->getVrSalario()) {
            $salario = $arIncapacidad->getVrSalario();
        }

        //Se empieza a liquidar las incapacidad
        $intVrSalarioEmpleado = $arContrato->getVrSalario();
        if ($arConfiguracion->getPagarIncapacidadSalarioPactado()) {
            if ($arContrato->getVrDevengadoPactado() != 0) {
                $intVrSalarioEmpleado = $arContrato->getVrDevengadoPactado();
            }
        }
        if ($arConfiguracion->getLiquidarIncapacidadesIbcMesAnterior()) {
            $anioIncapacidad = $arIncapacidad->getFechaDesde()->format('Y');
            $mesIncapacidad = $arIncapacidad->getFechaDesde()->format('m');
            if ($arIncapacidad->getEstadoProrroga()) {
                $arIncapacidadMesAnterior = $em->getRepository(RhuIncapacidad::class)->findOneBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'estadoProrroga' => 0), array('fechaDesde' => 'DESC'));
                if ($arIncapacidadMesAnterior) {
                    $anioIncapacidad = $arIncapacidadMesAnterior->getFechaDesde()->format('Y');
                    $mesIncapacidad = $arIncapacidadMesAnterior->getFechaDesde()->format('m');
                }
            }
            $arrIbc = $em->getRepository(RhuAporteDetalle::class)->ibcMesAnterior($anioIncapacidad, $mesIncapacidad, $arEmpleado->getCodigoEmpleadoPk());
            if ($arrIbc['respuesta'] == false) {
                if ($arIncapacidad->getVrSalario()) {
                    $arrIbc = array('respuesta' => true, 'ibc' => $arIncapacidad->getVrSalario(), 'dias' => 30);
                } else {
                    $arrIbc = array('respuesta' => true, 'ibc' => $intVrSalarioEmpleado, 'dias' => 30);
                }
            }
            if ($arContrato->getCodigoContratoClaseFk() == 4 || $arContrato->getCodigoContratoClaseFk() == 5) {
                $arrIbc = array('respuesta' => true, 'ibc' => $salario, 'dias' => 30);
            }
        } else {
            $arrIbc = array('respuesta' => true, 'ibc' => $intVrSalarioEmpleado, 'dias' => 30);
        }
        if ($arIncapacidad->getVrIbcPropuesto()) {
            $arrIbc = array('respuesta' => true, 'ibc' => $arIncapacidad->getVrIbcPropuesto(), 'dias' => 30);
        }
        if ($arrIbc['respuesta']) {
            $diasProrroga = 0;
            if ($arIncapacidad->getCodigoIncapacidadProrrogaFk()) {
                $arIncapacidadProrroga = $em->getRepository(RhuIncapacidad::class)->find($arIncapacidad->getCodigoIncapacidadProrrogaFk());
                if ($arIncapacidadProrroga) {
                    $arIncapacidad->setIncapacidadProrrogaRel($arIncapacidadProrroga);
                    $arIncapacidad->setEstadoProrroga(true);
                    $diasProrroga = $arIncapacidadProrroga->getDiasAcumulados();
                }
            }
            $intDias = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
            $intDias = $intDias->format('%a');
            $intDias = $intDias + 1;
            $intDiasLicencia = $this->diasReconocimiento($intDias, $arIncapacidad->getEstadoProrroga(), $arIncapacidad->getIncapacidadTipoRel()->getCodigoIncapacidadTipoPk(), $diasProrroga);
            $fechaDesdeCalculo = $arIncapacidad->getFechaDesde();
            if ($intDiasLicencia['intDiasEmpresa'] > 0) {
                $fechaDesdeCalculo = $arIncapacidad->getFechaDesde();
                $intDiasTemporal = $intDiasLicencia['intDiasEmpresa'] - 1;
                $fechaTemporal = $fechaDesdeCalculo->format('y-m-d');
                $fechaDesdeCalculo = date('y-m-d', strtotime($fechaTemporal . "+$intDiasTemporal days"));
                $arIncapacidad->setFechaDesdeEmpresa($arIncapacidad->getFechaDesde());
                $fechaDesdeCalculo = date_create($fechaDesdeCalculo);
                $arIncapacidad->setFechaHastaEmpresa($fechaDesdeCalculo);
                $fechaTemporal = $fechaDesdeCalculo->format('y-m-d');
                $fechaDesdeCalculo = date('y-m-d', strtotime($fechaTemporal . "+1 days"));
                $fechaDesdeCalculo = date_create($fechaDesdeCalculo);
            } else {
                $arIncapacidad->setFechaDesdeEmpresa(null);
                $arIncapacidad->setFechaHastaEmpresa(null);
            }
            if ($intDiasLicencia['intDiasEntidad'] > 0) {
                $intDiasTemporal = $intDiasLicencia['intDiasEntidad'] - 1;
                $arIncapacidad->setFechaDesdeEntidad($fechaDesdeCalculo);
                $fechaTemporal = $fechaDesdeCalculo->format('y-m-d');
                $fechaDesdeCalculo = date('y-m-d', strtotime($fechaTemporal . "+$intDiasTemporal days"));
                $fechaDesdeCalculo = date_create($fechaDesdeCalculo);
                $arIncapacidad->setFechaHastaEntidad($fechaDesdeCalculo);
            } else {
                $arIncapacidad->setFechaDesdeEntidad(null);
                $arIncapacidad->setFechaHastaEntidad(null);
            }
            $intDiasCobro = $intDiasLicencia['intDiasEntidad'];
            $arIncapacidad->setDiasEntidad($intDiasLicencia['intDiasEntidad']);
            $arIncapacidad->setDiasEmpresa($intDiasLicencia['intDiasEmpresa']);
            $arIncapacidad->setDiasCobro($intDiasCobro);
            $arIncapacidad->setCantidad($intDias);
            $arIncapacidad->setVrIbcMesAnterior($arrIbc['ibc']);
            $arIncapacidad->setDiasIbcMesAnterior($arrIbc['dias']);
            $floVrIncapacidad = 0;
            $floVrIncapacidadEmpleado = 0;
            $douVrDia = $arrIbc['ibc'] / $arrIbc['dias'];
            $douVrDiaSalarioMinimo = $salario / 30;
            $salarioEmpleado = $douVrDia * 30;
            $douPorcentajePago = $arIncapacidad->getIncapacidadTipoRel()->getConceptoRel()->getPorcentaje();
            $douPorcentajePagoEmpresa = $arIncapacidad->getIncapacidadTipoRel()->getConceptoEmpresaRel()->getPorcentaje();
            $arIncapacidad->setPorcentajePago($douPorcentajePago);
            if ($arIncapacidad->getIncapacidadTipoRel()->getCodigoIncapacidadTipoPk() == "GEN") {
                if ($salarioEmpleado <= $salario) {
                    if ($intDiasCobro > 0) {
                        $floVrIncapacidad = $intDiasCobro * $douVrDiaSalarioMinimo;
                    }
                    $floVrIncapacidadEmpleado = $intDias * $douVrDiaSalarioMinimo;
                    $douVrDia = $salario / $arrIbc['dias'];
                }
                if ($salarioEmpleado > $salario && $salarioEmpleado <= $salario * 1.5) {
                    if ($arConfiguracion->getPagarIncapacidadCompleta()) {
                        if ($intDiasCobro > 0) {
                            $floVrIncapacidad = $intDiasCobro * $douVrDia;
                        }
                        $floVrIncapacidadEmpleado = $intDias * $douVrDia;
                    } else {
                        if ($intDiasCobro > 0) {
                            $floVrIncapacidad = $intDiasCobro * $douVrDiaSalarioMinimo;
                        }
                        $floVrIncapacidadEmpleado = $intDias * $douVrDiaSalarioMinimo;
                        $douVrDia = $douVrDiaSalarioMinimo;
                    }
                }
                if ($salarioEmpleado > ($salario * 1.5)) {
                    $floVrIncapacidad = $intDiasCobro * $douVrDia;
                    $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago) / 100;
                    $floVrIncapacidadEmpleado = $intDias * $douVrDia;
                    $floVrIncapacidadEmpleado = ($floVrIncapacidadEmpleado * $douPorcentajePago) / 100;
                }
            } else {
                $floVrIncapacidad = $intDiasCobro * $douVrDia;
                $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago) / 100;
                $floVrIncapacidadEmpleado = $intDias * $douVrDia;
                $floVrIncapacidadEmpleado = ($floVrIncapacidadEmpleado * $douPorcentajePago) / 100;
            }
            if ($arIncapacidad->getVrPropuesto() > 0) {
                $floVrIncapacidadEmpleado = $arIncapacidad->getVrPropuesto();
            }
            $floVrIncapacidadEmpleado = round($floVrIncapacidadEmpleado);
            $douVrHora = ($floVrIncapacidadEmpleado / $intDias) / 8;
            $douVrHoraEmpresa = (($intDias * $douVrDia) / $intDias) / 8;
            $douVrHoraEmpresa = $douVrHoraEmpresa * $douPorcentajePagoEmpresa / 100;
            $vrHoraSalarioMinimo = $salarioMinimo / 30 / 8;
            if($douVrHoraEmpresa < $vrHoraSalarioMinimo) {
                $douVrHoraEmpresa = $vrHoraSalarioMinimo;
            }
            $arIncapacidad->setVrHora($douVrHora);
            $arIncapacidad->setVrHoraEmpresa($douVrHoraEmpresa);
            $arIncapacidad->setVrCobro($floVrIncapacidad);
            $arIncapacidad->setVrIncapacidad($floVrIncapacidadEmpleado);
            if ($arIncapacidad->getVrPagado()) {
                $floVrIncapacidadSaldo = $floVrIncapacidad - $arIncapacidad->getVrPagado();
                if ($floVrIncapacidadSaldo < 0) {
                    $floVrIncapacidadSaldo = 0;
                }
                $arIncapacidad->setVrSaldo($floVrIncapacidadSaldo);
            } else {
                $arIncapacidad->setVrSaldo($floVrIncapacidad);
            }
            if ($arIncapacidad->getCodigoIncapacidadProrrogaFk()) {
                if ($arIncapacidadProrroga) {
                    $diasAcumulados = $arIncapacidadProrroga->getDiasAcumulados() + $intDias;
                    $arIncapacidad->setDiasAcumulados($diasAcumulados);
                }
            } else {
                $arIncapacidad->setDiasAcumulados($intDias);
            }
            if (!$arIncapacidad->getEstadoCobrar()) {
                $arIncapacidad->setVrCobro(0);
            }
            if (!$arIncapacidad->getPagarEmpleado()) {
                $arIncapacidad->setVrIncapacidad(0);
            }
            $em->persist($arIncapacidad);
        } else {
            $respuesta = "No existe ibc mes anterior para liquidar la incapacidad debe generar seguridad social";
        }

        return $respuesta;
    }

    private function diasReconocimiento($diasIncapacidad, $prorroga = false, $tipo = "GEN", $diasProrroga): array
    {
        $intDiasEntidad = 0;
        $intDiasEmpresa = 0;
        if ($tipo == "GEN") {//si la incapacidad es general
            if ($diasIncapacidad > 2) {
                if ($prorroga) {
                    $intDiasEntidad = $diasIncapacidad;
                    $intDiasEmpresa = 0;
                } else {
                    $intDiasEntidad = $diasIncapacidad - 2;
                    $intDiasEmpresa = 2;
                }
            } else {
                if ($prorroga) {
                    if ($diasProrroga < 2) {
                        $intDiasEntidad = $diasProrroga + $diasIncapacidad - 2;
                        $intDiasEmpresa = 1;
                    } else {
                        $intDiasEntidad = $diasIncapacidad;
                        $intDiasEmpresa = 0;
                    }
                } else {
                    $intDiasEntidad = 0;
                    $intDiasEmpresa = $diasIncapacidad;
                }
            }
        }
        if ($tipo == "LAB" || $tipo == "ACL") {
            if ($diasIncapacidad > 1) {
                if ($prorroga) {
                    $intDiasEntidad = $diasIncapacidad;
                    $intDiasEmpresa = 0;
                } else {
                    $intDiasEntidad = $diasIncapacidad - 1;
                    $intDiasEmpresa = 1;
                }
            } else {
                $intDiasEntidad = 0;
                $intDiasEmpresa = $diasIncapacidad;
            }
        }
        return array('intDiasEntidad' => $intDiasEntidad, 'intDiasEmpresa' => $intDiasEmpresa);
    }

    public function diasIncapacidadPeriodo31($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM App\Entity\RecursoHumano\RhuIncapacidad incapacidad "
            . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
            . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) "
            . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        $objQuery = $em->createQuery($dql);
        $arIncapacidades = $objQuery->getResult();
        $intDiasIncapacidad = 0;
        foreach ($arIncapacidades as $arIncapacidad) {
            $intDiaInicio = 1;
            $intDiaFin = 30;
            if ($arIncapacidad->getFechaDesde() < $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');
            } else {
                $intDiaInicio = $arIncapacidad->getFechaDesde()->format('j');
            }
            if ($arIncapacidad->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');
            } else {
                $intDiaFin = $arIncapacidad->getFechaHasta()->format('j');
            }
            $intDiasIncapacidad += (($intDiaFin - $intDiaInicio) + 1);
        }
        return $intDiasIncapacidad;
    }

    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoGrupo = "")
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM App\Entity\RecursoHumano\RhuIncapacidad incapacidad "
            . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
            . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) ";
        if ($codigoEmpleado != "") {
            $dql = $dql . "AND incapacidad.codigoEmpleadoFk = " . $codigoEmpleado . " ";
        }
        if ($codigoGrupo != "") {
            $dql = $dql . "AND incapacidad.codigoGrupoFk = " . $codigoGrupo . " ";
        }
        $objQuery = $em->createQuery($dql);
        $arIncapacidades = $objQuery->getResult();
        return $arIncapacidades;
    }

    public function periodoEmpresa($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoGrupo = "")
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM App\Entity\RecursoHumano\RhuIncapacidad incapacidad "
            . "WHERE incapacidad.pagarEmpleado = 1 AND (((incapacidad.fechaDesdeEmpresa BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHastaEmpresa BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (incapacidad.fechaDesdeEmpresa >= '$strFechaDesde' AND incapacidad.fechaDesdeEmpresa <= '$strFechaHasta') "
            . "OR (incapacidad.fechaHastaEmpresa >= '$strFechaHasta' AND incapacidad.fechaDesdeEmpresa <= '$strFechaDesde')) ";
        if ($codigoEmpleado != "") {
            $dql = $dql . "AND incapacidad.codigoEmpleadoFk = " . $codigoEmpleado . " ";
        }
        if ($codigoGrupo != "") {
            $dql = $dql . "AND incapacidad.codigoGrupoFk = " . $codigoGrupo . " ";
        }
        $objQuery = $em->createQuery($dql);
        $arIncapacidades = $objQuery->getResult();
        return $arIncapacidades;
    }

    public function periodoEntidad($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoGrupo = "")
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM App\Entity\RecursoHumano\RhuIncapacidad incapacidad "
            . "WHERE incapacidad.pagarEmpleado = 1 AND (((incapacidad.fechaDesdeEntidad BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHastaEntidad BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (incapacidad.fechaDesdeEntidad >= '$strFechaDesde' AND incapacidad.fechaDesdeEntidad <= '$strFechaHasta') "
            . "OR (incapacidad.fechaHastaEntidad >= '$strFechaHasta' AND incapacidad.fechaDesdeEntidad <= '$strFechaDesde')) ";
        if ($codigoEmpleado != "") {
            $dql = $dql . "AND incapacidad.codigoEmpleadoFk = " . $codigoEmpleado . " ";
        }
        if ($codigoGrupo != "") {
            $dql = $dql . "AND incapacidad.codigoGrupoFk = " . $codigoGrupo . " ";
        }
        $objQuery = $em->createQuery($dql);
        $arIncapacidades = $objQuery->getResult();
        return $arIncapacidades;
    }

    public function diasProgramacion($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuIncapacidad::class, 'i')
            ->select('i.codigoIncapacidadPk')
            ->addSelect('i.fechaDesde')
            ->addSelect('i.fechaHasta')
            ->andWhere("(((i.fechaDesde BETWEEN '$fechaDesde' AND '$fechaHasta') OR (i.fechaHasta BETWEEN '$fechaDesde' AND '$fechaHasta')) "
                . "OR (i.fechaDesde >= '$fechaDesde' AND i.fechaDesde <= '$fechaHasta') "
                . "OR (i.fechaHasta >= '$fechaHasta' AND i.fechaDesde <= '$fechaDesde')) "
                . "AND i.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND i.cantidad > 0 AND i.estadoAnulado = 0")
            ->andWhere('i.pagarEmpleado = 1');
        if ($codigoContrato) {
            $query->andWhere("i.codigoContratoFk = " . $codigoContrato);
        }
        $arIncapacidades = $query->getQuery()->getResult();
        $intDiasIncapacidad = 0;
        foreach ($arIncapacidades as $arIncapacidad) {
            $dateFechaDesde = date_create($fechaDesde);
            $dateFechaHasta = date_create($fechaHasta);
            if ($arIncapacidad['fechaDesde'] < $dateFechaDesde) {
                $dateFechaDesde = $dateFechaDesde;
            } else {
                $dateFechaDesde = $arIncapacidad['fechaDesde'];
            }

            if ($arIncapacidad['fechaHasta'] > $dateFechaHasta) {
                $dateFechaHasta = $dateFechaHasta;
            } else {
                $dateFechaHasta = $arIncapacidad['fechaHasta'];
            }
            $intDias = $dateFechaDesde->diff($dateFechaHasta);
            $intDias = $intDias->format('%a');
            $intDias += 1;
            $intDiasIncapacidad += $intDias;
        }
        $arrDevolver = array('dias' => $intDiasIncapacidad);
        return $arrDevolver;
    }

    public function listaIncapacidadMes($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(RhuIncapacidad::class, "i")
            ->select("i.codigoIncapacidadPk, i.fechaDesde, i.fechaHasta, i.vrIncapacidad, i.cantidad, it.generaIbc")
            ->join("i.incapacidadTipoRel", "it")
            ->where("i.fechaDesde <= '{$fechaHasta->format('Y-m-d')}' AND  i.fechaHasta >= '{$fechaHasta->format('Y-m-d')}'")
            ->orWhere("i.fechaDesde <= '{$fechaDesde->format('Y-m-d')}' AND  i.fechaHasta >='{$fechaDesde->format('Y-m-d')}' AND i.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->orWhere("i.fechaDesde >= '{$fechaDesde->format('Y-m-d')}' AND  i.fechaHasta <='{$fechaHasta->format('Y-m-d')}' AND i.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->andWhere("i.codigoEmpleadoFk = '{$codigoEmpleado}'");

        $arrIncapacidadesEmpleado = $qb->getQuery()->execute();
        return $arrIncapacidadesEmpleado;
    }

}