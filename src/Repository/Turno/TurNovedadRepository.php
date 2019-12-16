<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurNovedad;
use App\Entity\Turno\TurProgramacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurNovedadRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurNovedad::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEmpleado = null;
        $codigoNovedad = null;
        $novedadTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        $estadoAplicado = null;

        if ($filtros) {
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $codigoNovedad = $filtros['codigoNovedad'] ?? null;
            $novedadTipo = $filtros['novedadTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
            $estadoAplicado = $filtros['$estadoAplicado'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurNovedad::class, 'n')
            ->select('n.codigoNovedadPk')
            ->addSelect('n.fechaDesde')
            ->addSelect('n.fechaHasta')
            ->addSelect('n.estadoAutorizado')
            ->addSelect('n.estadoAprobado')
            ->addSelect('n.estadoAnulado')
            ->addSelect('n.estadoAplicada')
            ->addSelect('n.usuario')
            ->addSelect('n.origen')
            ->addSelect('nt.nombre')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('er.nombreCorto as reemplazo')
            ->leftJoin('n.novedadTipoRel', 'nt')
            ->leftJoin('n.empleadoRel', 'e')
            ->leftJoin('n.empleadoReemplazoRel', 'er');

        if ($novedadTipo) {
            $queryBuilder->andWhere("n.codigoNovedadTipoFk = '{$novedadTipo}'");
        }

        if ($codigoEmpleado) {
            $queryBuilder->andWhere("n.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }

        if ($codigoNovedad) {
            $queryBuilder->andWhere("n.codigoNovedadPk = '{$codigoNovedad}'");
        }

        if ($fechaDesde) {
            $queryBuilder->andWhere("n.fechaDesde >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("n.fechaHasta <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("n.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("n.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("n.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoAnulado = 1");
                break;
        }
        switch ($estadoAplicado) {
            case '0':
                $queryBuilder->andWhere("n.estadoAplicado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoAplicado = 1");
                break;
        }

        $queryBuilder->addOrderBy('n.codigoNovedadPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

//    public function eliminar($arrSeleccionados)
//    {
//        /**
//         * @var TurProgramacionDetalle $arProgramacionDetalle
//         */
//        $em = $this->getEntityManager();
//        $respuesta = '';
//        if ($arrSeleccionados) {
//            $arrCodigoProgramacion = [];
//            foreach ($arrSeleccionados AS $codigo) {
//                $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigo);
//                if ($arNovedad) {
//                    $strAnio = $arNovedad->getFechaDesde()->format("Y");
//                    $strMes = $arNovedad->getFechaDesde()->format("m");
//                    $strDiaDesde = $arNovedad->getFechaDesde()->format('j');
//                    $strDiaHasta = $arNovedad->getFechaHasta()->format('j');
//                    $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arNovedad->getCodigoRecursoFk()), array('horas' => 'DESC'));
//                    $respuesta = $this->eliminarNovedadesRecursoHumano($arNovedad);
//
//                    if ($respuesta == '') {
//                        foreach ($arProgramacionDetalles AS $arProgramacionDetalle) {
//                            for ($i = $strDiaDesde; $i <= $strDiaHasta; $i++) {
//                                $turnoBackup = call_user_func_array([$arProgramacionDetalle, "getDiaN{$i}"], []);
//                                if ($turnoBackup != null) {
//                                    $arrCodigoProgramacion[] = $arProgramacionDetalle->getProgramacionRel()->getCodigoProgramacionPk();
//                                    # Seteamos el turno anterior
//                                    call_user_func_array([$arProgramacionDetalle, "setDia{$i}"], [$turnoBackup]);
//                                    call_user_func_array([$arProgramacionDetalle, "setDiaN{$i}"], [null]);
//                                }
//                                $em->persist($arProgramacionDetalle);
//                            }
//                            $em->getRepository('BrasaTurnoBundle:TurProgramacion')->actualizarLinea($arProgramacionDetalle);
//                        }
//                        if ($arNovedad->getCodigoRecursoReemplazoFk() != '') {
//                            $arProgramacionDetallesReemplazo = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(['anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arNovedad->getCodigoRecursoReemplazoFk()], ['horas' => 'DESC']);
//                            $diaBloqueoPeriodoProgramacion = $em->getRepository("BrasaTurnoBundle:TurCierreProgramacionPeriodo")->getDiaHastaBloqueo($arProgramacionDetalle->getAnio(), $arProgramacionDetalle->getMes());
//                            foreach ($arProgramacionDetallesReemplazo as $arProgramacionDetalleReemplazo) {
//                                # Se recorren todos los días y se setean los turnos de la nueva linea de programación
//                                for ($i = $strDiaDesde; $i <= $strDiaHasta; $i++) {
//                                    if ($i > $diaBloqueoPeriodoProgramacion) {
//                                        $codigoTurnoAct = call_user_func_array([$arProgramacionDetalleReemplazo, "getDia{$i}"], []);
//                                        if ($codigoTurnoAct != null) {
//                                            $arrCodigoProgramacion[] = $arProgramacionDetalleReemplazo->getProgramacionRel()->getCodigoProgramacionPk();
//                                            call_user_func_array([$arProgramacionDetalleReemplazo, "setDia{$i}"], [null]);
//                                        }
//                                    }
//                                }
//                                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->actualizarLinea($arProgramacionDetalleReemplazo);
//                            }
//                        }
//                        $em->remove($arNovedad);
//                    } else {
//                        break;
//                    }
//                }
//            }
//            if ($respuesta == '') {
//                try {
//                    $em->flush();
//                    $arrCodigoProgramacion = array_unique($arrCodigoProgramacion);
//                    foreach ($arrCodigoProgramacion as $codigoProgramacion) {
//                        $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
//                    }
//                } catch (\Exception $exception) {
//                    $respuesta = "No se puede eliminar, el/los registros están siendo utilizados en el sistema";
//                }
//            }
//        }
//        return $respuesta;
//    }


//    public function aplicar($codigoNovedad, $boolReemplazo = 1, $boolCambioTipo = 0, $usuario = "")
//    {
//        $em = $this->getEntityManager();
//        $arNovedad = $em->getRepository(TurNovedad::class)->find($codigoNovedad);
//        $strAnio = $arNovedad->getFechaDesde()->format('Y');
//        $strMes = $arNovedad->getFechaDesde()->format('m');
//        $strMesHasta = $arNovedad->getFechaHasta()->format('m');
//        $strDiaDesde = $arNovedad->getFechaDesde()->format('j');
//        $strDiaHasta = $arNovedad->getFechaHasta()->format('j');
//        $respuesta = "";
//        if ($strMes != $strMesHasta) {
//            $strDiaHasta = $strUltimoDiaMes = date("j", (mktime(0, 0, 0, $arNovedad->getFechaDesde()->format('m') + 1, 1, $arNovedad->getFechaDesde()->format('Y')) - 1));
//        }
//        //Se guardan las novedades del mes actual.
//        $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy(array(
//            'anio' => $strAnio,
//            'mes' => $strMes,
//            'codigoEmpleadoFk' => $arNovedad->getCodigoEmpleadoFk()),
//            array('horas' => 'DESC'));
//        $respuesta = $this->guardarNovedadTurno($arProgramaciones, $arNovedad, $usuario, $strDiaDesde, $strDiaHasta, $strAnio, $strMes);
//        //Se terminan de guardar las novedades del mes actual.
//
//        //Se valida que contenga otro mes para consultar si tiene programacion del otro mes.
//        if ($strMes != $strMesHasta) {
//            $strDiaDesde = $strPrimerDiaMes = date("j", (mktime(0, 0, 0, $arNovedad->getFechaHasta()->format('m'), 1, $arNovedad->getFechaDesde()->format('Y'))));
//            $strDiaHasta = $arNovedad->getFechaHasta()->format('j');
//            $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array(
//                'anio' => $strAnio,
//                'mes' => $strMesHasta,
//                'codigoRecursoFk' => $arNovedad->getCodigoRecursoFk()),
//                array('horas' => 'DESC'));
//            $respuesta = $this->guardarNovedadTurno($arProgramacionDetalles, $arNovedad, $usuario, $strDiaDesde, $strDiaHasta, $strAnio, $strMes);
//        }
//
//        $arNovedad->setEstadoAplicada(1);
//        $em->persist($arNovedad);
//        $em->flush();
//        return $respuesta;
//    }
//
//    private function guardarNovedadTurno($arProgramaciones, $arNovedad, $usuario, $strDiaDesde, $strDiaHasta, $strAnio, $strMes)
//    {
//        /**
//         * @var TurNovedadTipo $arNovedadTipo
//         * @var TurPedidoDetalle $arPedidoDetalle
//         * @var TurProgramacionDetalle $arProgramacionDetalle
//         */
//        $diaControl = 1;
//        $em = $this->getEntityManager();
//        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
//        $boolReemplazo = $arNovedad->getCodigoEmpleadoReemplazoFk() != "";
//        # primero guardamos la novedad ( Si la configuración lo indica ).
//        if ($arConfiguracion->getProgramacionGuardarNovedadEnRhu()) {
//            $respuestaGuardar = $this->guardarNovedadRHU($arNovedad, $usuario);
//            if (is_array($respuestaGuardar)) {
//                return $respuestaGuardar;
//            }
//        }
//
//        foreach ($arProgramaciones as $arProgramacion) {
//            $arProgramacionDetalleAct = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());
//            $diaBloqueoPeriodoProgramacion = $em->getRepository("BrasaTurnoBundle:TurCierreProgramacionPeriodo")->getDiaHastaBloqueo($arProgramacionDetalle->getAnio(), $arProgramacionDetalle->getMes());
//
//            //Actualizar o crear programacion para el recurso reemplazo
////            $conReemplazo = $boolReemplazo == 1 && $diaControl == 1;
//            $conReemplazo = $boolReemplazo == 1;
//            if ($conReemplazo) {
//                if ($arNovedad->getCodigoRecursoReemplazoFk() != '') {
//                    $nuevaLineaProgramacion = new TurProgramacionDetalle();
//                    $nuevaLineaProgramacion->setProgramacionRel($arProgramacionDetalle->getProgramacionRel());
//                    $nuevaLineaProgramacion->setAnio($arProgramacionDetalle->getAnio());
//                    $nuevaLineaProgramacion->setMes($arProgramacionDetalle->getMes());
//                    $nuevaLineaProgramacion->setRecursoRel($arNovedad->getRecursoReemplazoRel());
//                    $nuevaLineaProgramacion->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
//                    $nuevaLineaProgramacion->setPuestoRel($arProgramacionDetalle->getPuestoRel());
//                    # Se recorren todos los días y se setean los turnos de la nueva linea de programación
//                    for ($i = $strDiaDesde; $i <= $strDiaHasta; $i++) {
//                        if ($i > $diaBloqueoPeriodoProgramacion) {
//                            $codigoTurnoAct = call_user_func_array([$arProgramacionDetalle, "getDia{$i}"], []);
//                            if ($codigoTurnoAct != null) {
//                                $arTurno = $em->getRepository("BrasaTurnoBundle:TurTurno")->find($codigoTurnoAct);
////                                if ($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
//                                // se quita la validacion de descanso para que  pueda pasar toda la programacion al recurso incluyendo los descansos
//                                if ($arTurno->getNovedad() == 0) {
//                                    call_user_func_array([$nuevaLineaProgramacion, "setDia{$i}"], [$codigoTurnoAct]);
//                                    $em->persist($nuevaLineaProgramacion);
//                                    // limpiamos los turnos del recurso que reemplaza
//                                    $arProgramacionesDetalleRecursoReemplaza = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array(
//                                        'mes' => $strMes,
//                                        'codigoRecursoFk' => $arNovedad->getCodigoRecursoReemplazoFk()),
//                                        array('horas' => 'DESC'));
//
//                                    foreach ($arProgramacionesDetalleRecursoReemplaza as $arProgramacionDetalleRecursoReemplaza) {
//                                        $arProgramacionDetalleRecursoReemplazaAct = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalleRecursoReemplaza->getCodigoProgramacionDetallePk());
//                                        $turnoActual = call_user_func_array([$arProgramacionDetalleRecursoReemplaza, "getDia{$i}"], []);
//                                        if ($turnoActual != null) {
//                                            call_user_func_array([$arProgramacionDetalleRecursoReemplazaAct, "setDia{$i}"], [null]);
//                                            $em->getRepository('BrasaTurnoBundle:TurProgramacion')->actualizarLinea($nuevaLineaProgramacion);
//                                            # Guardamos el turno que tenía antes.
//                                            call_user_func_array([$arProgramacionDetalleRecursoReemplazaAct, "setDiaN{$i}"], [$turnoActual]);
//                                            $em->persist($arProgramacionDetalleRecursoReemplazaAct);
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->actualizarLinea($nuevaLineaProgramacion);
//                }
//            }
//
//            $arPedidoDetalle = $arProgramacionDetalle->getPedidoDetalleRel();
//            # Actualizar recurso original
//            for ($i = $strDiaDesde; $i <= $strDiaHasta; $i++) {
////                se valida si tiene el dia bloqueo para modificar el dia de la programacion
//                if ($i > $arProgramacionDetalleAct->getPeriodoBloqueo() || $i > $diaBloqueoPeriodoProgramacion) {
//                    $codigoTurnoNuevo = $arNovedad->getNovedadTipoRel()->getCodigoTurnoFk();
//                    $turnoActual = call_user_func_array([$arProgramacionDetalleAct, "getDia{$i}"], []);
//                    # Seteamos el nuevo turno.
//                    call_user_func_array([$arProgramacionDetalleAct, "setDia{$i}"], [$diaControl == 1 ? $codigoTurnoNuevo : null]);
//                    if ($codigoTurnoNuevo != $turnoActual) {
//                        # Guardamos el turno que tenía antes.
//                        call_user_func_array([$arProgramacionDetalleAct, "setDiaN{$i}"], [$turnoActual]);
//                    }
//                    # Validamos que si haya un turno en el día que se recorre.
//                    if (!$boolReemplazo && $turnoActual != "" && $arTurno = $em->getRepository("BrasaTurnoBundle:TurTurno")->find($turnoActual)) {
//                        $horasDiurnas = $arTurno->getHorasDiurnas();
//                        $horasNocturnas = $arTurno->getHorasNocturnas();
//                        $arPedidoDetalle->setHorasDiurnasProgramadas($arPedidoDetalle->getHorasDiurnasProgramadas() - $horasDiurnas);
//                        $arPedidoDetalle->setHorasNocturnasProgramadas($arPedidoDetalle->getHorasNocturnasProgramadas() - $horasNocturnas);
//                        $arProgramacionDetalleAct->setHorasDiurnas($arProgramacionDetalle->getHorasDiurnas() - $horasDiurnas);
//                        $arProgramacionDetalleAct->setHorasNocturnas($arProgramacionDetalle->getHorasNocturnas() - $horasNocturnas);
//                    }
//                }
//            }
//            $em->getRepository('BrasaTurnoBundle:TurProgramacion')->actualizarLinea($arProgramacionDetalle);
//
//            $em->persist($arProgramacionDetalleAct);
//            $em->persist($arPedidoDetalle);
//            $em->flush();
//            $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($arProgramacionDetalleAct->getProgramacionRel()->getCodigoProgramacionPk());
//            $diaControl++;
//        }
//    }
//
//    /**
//     * @param TurNovedad $arNovedad
//     * @param string $usuario
//     * @return boolean|array
//     */
//    private function guardarNovedadRHU($arNovedad, $usuario = "")
//    {
//        $em = $this->getEntityManager();
//        $arEmpleado = $em->getRepository("BrasaRecursoHumanoBundle:RhuEmpleado")->find($arNovedad->getCodigoRecursoFk());
//        $codigoContrato = $arEmpleado->getCodigoContratoActivoFk() ? $arEmpleado->getCodigoContratoActivoFk() : $arEmpleado->getCodigoContratoUltimoFk();
//        # Si no hay empleado asociado al recurso no se hace nada.
//        if (!$arEmpleado || !$codigoContrato) {
//            return false;
//        }
//        $arContrato = $em->getRepository("BrasaRecursoHumanoBundle:RhuContrato")->find($codigoContrato);
//        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
//
//        if ($arNovedad->getNovedadTipoRel()->getEsIncapacidad()) {
//            return $this->guardarIncapacidad($em, $arNovedad, $arEmpleado, $arContrato, $arConfiguracion, $usuario);
//        } else if ($arNovedad->getNovedadTipoRel()->getEsLicencia()) {
//            return $this->guardarLicencia($em, $arNovedad, $arEmpleado, $arContrato, $arConfiguracion, $usuario);
//        }
//        return false;
//    }
//
//    /**
//     * @param EntityManager $em
//     * @param TurNovedad $arNovedad
//     * @param RhuEmpleado $arEmpleado
//     * @param RhuContrato $arContrato
//     * @param RhuConfiguracion $arConfiguracion
//     * @param string $usuario
//     * @return bool
//     */
//    private function guardarIncapacidad($em, $arNovedad, $arEmpleado, $arContrato, $arConfiguracion, $usuario)
//    {
//        $arIncapacidad = new RhuIncapacidad();
//        $arIncapacidad->setEmpleadoRel($arEmpleado);
//        $arIncapacidad->setContratoRel($arContrato);
//        $arIncapacidad->setFechaDesde($arNovedad->getFechaDesde());
//        $arIncapacidad->setFechaHasta($arNovedad->getFechaHasta());
//
//        return true;
//    }
//
//    /**
//     * @param EntityManager $em
//     * @param TurNovedad $arNovedad
//     * @param RhuEmpleado $arEmpleado
//     * @param RhuContrato $arContrato
//     * @param RhuConfiguracion $arConfiguracion
//     * @param string $usuario
//     * @return bool
//     */
//    private function guardarLicencia($em, $arNovedad, $arEmpleado, $arContrato, $arConfiguracion, $usuario)
//    {
//
//        $arLicencia = new RhuLicencia();
//        if ($arNovedad->getEstadoAplicada() == 1) {
//            $arLicencia = $em->getRepository("BrasaRecursoHumanoBundle:RhuLicencia")->findOneBy(array('codigoNovedadProgramacion' => $arNovedad->getCodigoNovedadPk()));
//            if (!$arLicencia) {
//                $arLicencia = new RhuLicencia();
//            }
//        }
//
//        $codigoTurno = $arNovedad->getNovedadTipoRel()->getCodigoTurnoFk();
//        $arLicenciaTipo = $em->getRepository("BrasaRecursoHumanoBundle:RhuLicenciaTipo")->findOneby(['tipoNovedadTurno' => $codigoTurno]);
//        # Si no existe el tipo de licencia asociado para la novedad.
//        if (!$arLicenciaTipo) return false;
//        $licenciasExistentes = $this->validarFechaLicencia($em, $arNovedad, $arEmpleado, $arContrato, $arConfiguracion);
//        # Si hay licencias las retornamos.
//        if ($licenciasExistentes && $arNovedad->getEstadoAplicada() == 0 && $arLicencia->getCodigoLicenciaPk() == null) {
//            return true;
//        }
//        $arLicencia->setFechaDesde($arNovedad->getFechaDesde());
//        $arLicencia->setFechaHasta($arNovedad->getFechaHasta());
//        $arLicencia->setLicenciaTipoRel($arLicenciaTipo);
//        $arLicencia->setEmpleadoRel($arEmpleado);
//        $arLicencia->setCentroCostoRel($arEmpleado->getCentroCostoRel());
//        $arLicencia->setContratoRel($arContrato);
//        $arLicencia->setFecha(new \DateTime(date("Y-m-d H:i:s")));
//        $diff = $arNovedad->getFechaDesde()->diff($arNovedad->getFechaHasta());
//        $dias = intval($diff->format("%a")) + 1;
//        $porcentajePago = doubleval($arLicencia->getLicenciaTipoRel()->getPagoConceptoRel()->getPorPorcentaje());
//        $arLicencia->setDiasCobro($dias);
//        $arLicencia->setCantidad($dias);
//        $arLicencia->setMaternidad($arLicencia->getLicenciaTipoRel()->getMaternidad());
//        $arLicencia->setPaternidad($arLicencia->getLicenciaTipoRel()->getPaternidad());
//        $arLicencia->setEntidadSaludRel($arEmpleado->getEntidadSaludRel());
//        $vrDia = $arEmpleado->getVrSalario() / 30;
//        $vrDiaMinimo = $arConfiguracion->getVrSalario() / 30;
//        $arLicencia->setPorcentajePago($porcentajePago);
//        $arLicencia->setAfectaTransporte(true);
//        $arLicencia->setPagarEmpleado(true);
//        $arLicencia->setCodigoNovedadProgramacion($arNovedad->getCodigoNovedadPk());
//        if ($arLicencia->getLicenciaTipoRel()->getMaternidad() || $arLicencia->getLicenciaTipoRel()->getPaternidad()) {
//            $vrLicencia = $arEmpleado->getVrSalario() <= $arConfiguracion->getVrSalario() ? $dias * $vrDiaMinimo : $dias * $vrDia;
//        } else {
//            $vrLicencia = 0;
//        }
//        $arLicencia->setVrCobro(round($vrLicencia));
//        $arLicencia->setVrLicencia(round($vrLicencia));
//        $arLicencia->setVrSaldo(round($vrLicencia));
//        $arLicencia->setCodigoUsuario($usuario);
//        $em->persist($arLicencia);
//        $em->flush();
//        return true;
//    }
//
//    /**
//     * @param EntityManager $em
//     * @param TurNovedad $arNovedad
//     * @param RhuEmpleado $arEmpleado
//     * @param RhuContrato $arContrato
//     * @param RhuConfiguracion $arConfiguracion
//     * @return bool|array
//     */
//    private function validarFechaLicencia($em, $arNovedad, $arEmpleado, $arContrato, $arConfiguracion)
//    {
//
//        $fechaDesde = $arNovedad->getFechaDesde()->format("Y-m-d");
//        $fechaHasta = $arNovedad->getFechaHasta()->format("Y-m-d");
//        $strCondicion = "((l.fechaDesde >= '{$fechaDesde}' AND l.fechaDesde <= '{$fechaHasta}') OR
//                        (l.fechaDesde <= '{$fechaDesde}') AND l.fechaHasta >= '{$fechaDesde}')";
//        $qb = $em->createQueryBuilder();
//        $qb->from("BrasaRecursoHumanoBundle:RhuLicencia", "l")
//            ->select("l")
//            ->where("l.codigoEmpleadoFk = '{$arEmpleado->getCodigoEmpleadoPk()}' AND " . $strCondicion);
//        /**
//         * @var RhuLicencia[] $licencias
//         */
//        $licencias = $qb->getQuery()->execute();
//        if (count($licencias) == 0) return false;
//        $licenciasEncontradas = [];
//
//        foreach ($licencias AS $licencia) {
//            $licenciasEncontradas[] = $licencia->getFechaDesde()->format("Y-m-d") . " HASTA " . $licencia->getFechaHasta()->format("Y-m-d");
//        }
//        return $licenciasEncontradas;
//    }
//
//    public function validarEmpleados($codigoNovedad)
//    {
//
//        $em = $this->getEntityManager();
//        $arConfiguracion = $em->getRepository("BrasaTurnoBundle:TurConfiguracion")->find(1);
//        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);
//
//        $strAnio = $arNovedad->getFechaDesde()->format('Y');
//        $strMes = $arNovedad->getFechaDesde()->format('m');
//
//        $respuesta = "";
//
//        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array(
//            'anio' => $strAnio,
//            'mes' => $strMes,
//            'codigoRecursoFk' => $arNovedad->getCodigoRecursoFk()),
//            array('horas' => 'DESC'));
//
//        foreach ($arProgramacionDetalles AS $arProgramacionDetalle) {
//
//            if ($arNovedad->getRecursoReemplazoRel() != null) {
//                if ($arNovedad->getRecursoReemplazoRel()->getLimitarCliente() == 1) {
//                    if ($arProgramacionDetalle->getProgramacionRel()->getCodigoClienteFk() != $arNovedad->getRecursoReemplazoRel()->getCodigoClienteFk()) {
//                        $respuesta = "La novedad '{$arNovedad->getCodigoNovedadPk()}' no se puede aplicar, el recurso de reemplazo está limitado a un cliente diferente " .
//                            "al de la programacion del recurso";
//                        break;
//                    }
//                }
//            }
//
//            if (($arProgramacionDetalle->getPedidoDetalleRel()->getCodigoModalidadServicioFk() == $arConfiguracion->getCodigoModalidadArma())) {
//                if ($arNovedad->getRecursoReemplazoRel() != null) {
//                    if ($arNovedad->getRecursoReemplazoRel()->getRestriccionArma() == 1) {
//                        $respuesta = "La novedad '{$arNovedad->getCodigoNovedadPk()}' no se puede aplicar, el servicio detalle '{$arProgramacionDetalle->getPedidoDetalleRel()->getServicioDetalleRel()->getPuestoRel()->getNombre()}'" .
//                            " en el servicio '{$arProgramacionDetalle->getPedidoDetalleRel()->getServicioDetalleRel()->getServicioRel()->getCodigoServicioPk()}' tiene modalidad de arma y el reemplazo tiene restriccion";
//                        break;
//                    }
//                }
//            }
//        }
//
//        return $respuesta;
//
//    }
}