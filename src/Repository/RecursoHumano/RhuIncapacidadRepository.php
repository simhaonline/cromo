<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuIncapacidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuIncapacidadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuIncapacidad::class);
    }


    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncapacidad::class, 'i');
        $queryBuilder
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
            ->leftJoin('i.empleadoRel', 'e');

        if ($session->get('filtroRhuIncapacidadCodigoEmpleado') != null) {
            $queryBuilder->andWhere("i.codigoEmpleadoFk = '{$session->get('filtroRhuIncapacidadCodigoEmpleado')}'");
        }
        if ($session->get('filtroRhuIncapacidadCodigoEntidadSalud') != null) {
            $queryBuilder->andWhere("i.codigoEntidadSaludFk = '{$session->get('filtroRhuIncapacidadCodigoEntidadSalud')}'");
        }
        if ($session->get('filtroRhuIncapacidadCodigoGrupo') != null) {
            $queryBuilder->andWhere("i.codigoGrupoFk = '{$session->get('filtroRhuIncapacidadCodigoGrupo')}'");
        }
        if ($session->get('filtroRhuIncapacidadNumeroEps') != null) {
            $queryBuilder->andWhere("i.numeroEps = '{$session->get('filtroRhuIncapacidadNumeroEps')}'");
        }
        if ($session->get('filtroRhuIncapacidadLegalizada') != null) {
            $queryBuilder->andWhere("i.estadoLegalizado = '{$session->get('filtroRhuIncapacidadLegalizada')}'");
        }
        if ($session->get('filtroRhuIncapacidadEstadoTranscripcion') != null) {
            $queryBuilder->andWhere("i.estadoTranscripcion = '{$session->get('filtroRhuIncapacidadEstadoTranscripcion')}'");
        }
        if ($session->get('filtroRhuIncapacidadTipoIncapacidad') != null) {
            $queryBuilder->andWhere("i.codigoIncapacidadTipoFk = '{$session->get('filtroRhuIncapacidadTipoIncapacidad')}'");
        }
        if ($session->get('filtroRhuIncapacidadFechaDesde') != null) {
            $queryBuilder->andWhere("i.fechaDesde >= '{$session->get('filtroRhuIncapacidadFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuIncapacidadFechaHasta') != null) {
            $queryBuilder->andWhere("i.fechaHasta <= '{$session->get('filtroRhuIncapacidadFechaHasta')} 23:59:59'");
        }

        return $queryBuilder;
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

    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoIncapacidad = ""):bool
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
        $salario = $arConfiguracion->getVrSalarioMinimo();
        if($arIncapacidad->getVrSalario()){
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
            $arrIbc = $em->getRepository( RhuAporteDetalle::class)->ibcMesAnterior($anioIncapacidad, $mesIncapacidad, $arEmpleado->getCodigoEmpleadoPk());
            if ($arrIbc['respuesta'] == false) {
                if($arIncapacidad->getVrSalario()) {
                    $arrIbc = array('respuesta' => true, 'ibc' => $arIncapacidad->getVrSalario(), 'dias' => 30);
                }else{
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
                $arIncapacidad->setFechaDesdeEntidad($fechaDesdeCalculo??0);
                $fechaTemporal = $fechaDesdeCalculo->format('y-m-d')??null;
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
                    if($arConfiguracion->getPagarIncapacidadCompleta()) {
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
                if ($salarioEmpleado > ($salario * 1.5) || $arConfiguracion->getLiquidarIncapacidadSinBase()) {
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
            $arIncapacidad->setVrHora($douVrHora);
            $arIncapacidad->setVrHoraEmpresa($douVrHoraEmpresa);
            $arIncapacidad->setVrCobro($floVrIncapacidad);
            $arIncapacidad->setVrIncapacidad($floVrIncapacidadEmpleado);
            if($arIncapacidad->getVrPagado()){
                $floVrIncapacidadSaldo = $floVrIncapacidad - $arIncapacidad->getVrPagado();
                if($floVrIncapacidadSaldo < 0){
                    $floVrIncapacidadSaldo = 0;
                }
                $arIncapacidad->setVrSaldo($floVrIncapacidadSaldo);
            }else {
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

    private function diasReconocimiento($diasIncapacidad, $prorroga = false, $tipo = 1, $diasProrroga):array
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
        if ($tipo == "LAB") {
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
}