<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConsecutivo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Seguridad\Usuario;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function getNeto($id){
        return $this->_em->createQueryBuilder()
            ->from(RhuProgramacion::class,'p')
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
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->cargarContratos();
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
            $arProgramacionDetalle->setVrSalario($arContrato->getVrSalario());

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
            $arrIbc = $em->getRepository(RhuPagoDetalle::class)->ibcMes($fechaDesde->format('Y'), $fechaDesde->format('m'), $arContrato->getCodigoContratoPk(), $arConfiguracion['codigoConceptoFondoPensionFk']);
            $arProgramacionDetalle->setVrIbcAcumulado($arrIbc['ibc']);
            $arProgramacionDetalle->setVrDeduccionFondoPensionAnterior($arrIbc['deduccionAnterior']);
            $arrVacaciones = $em->getRepository(RhuVacacion::class)->diasProgramacion($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacion->getFechaDesde()->format('Y-m-d'), $arProgramacion->getFechaHasta()->format('Y-m-d'));
            $arProgramacionDetalle->setDiasVacaciones($arrVacaciones['dias']);
            $dias -= $arrVacaciones['dias'];
            $horas = $dias * $arContrato->getFactorHorasDia();
            $arProgramacionDetalle->setDias($dias);
            $arProgramacionDetalle->setDiasTransporte($dias);
            $arProgramacionDetalle->setHorasDiurnas($horas);


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
        $em = $this->getEntityManager();
        if($arProgramacion->getEstadoAutorizado() == 1 && $arProgramacion->getEstadoAprobado() == 0) {
            $arProgramacion->setEstadoAprobado(1);
            $em->persist($arProgramacion);
            $arConsecutivo = $em->getRepository(RhuConsecutivo::class)->find(1);
            $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()));
            foreach ($arPagos as $arPago) {
                $arPago->setNumero($arConsecutivo->getPago());
                $arPago->setEstadoAutorizado(1);
                $arPago->setEstadoAprobado(1);
                $em->persist($arPago);
                $arConsecutivo->setPago($arConsecutivo->getPago() + 1);
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
    private function setVrNeto($codigoProgramacion){
        $this->_em->createQueryBuilder()
            ->update(RhuProgramacionDetalle::class,'pd')
            ->set('pd.vrNeto','?1')
            ->where("pd.codigoProgramacionFk = {$codigoProgramacion}")
            ->setParameter('1',0)
            ->getQuery()->execute();
    }

    public function generar($arProgramacion, $codigoProgramacionDetalle, $usuario) {
        $em = $this->getEntityManager();
        $douNetoTotal = 0;
        $numeroPagos = 0;
        $arConceptoHora = $em->getRepository(RhuConceptoHora::class)->findAll();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->autorizarProgramacion();
        $arConceptoFondoPension = $em->getRepository(RhuConcepto::class)->find($arConfiguracion['codigoConceptoFondoPensionFk']);
        if($codigoProgramacionDetalle) {
            $arProgramacionDetalleActualizar = $em->getRepository(RhuProgramacionDetalle::class)->find($codigoProgramacionDetalle);
            $vrNeto = $em->getRepository(RhuPago::class)->generar($arProgramacionDetalleActualizar, $arProgramacion, $arConceptoHora, $arConfiguracion, $arConceptoFondoPension, $usuario);
            $arProgramacionDetalleActualizar->setVrNeto($vrNeto);
            $em->persist($arProgramacionDetalleActualizar);
            $arProgramacion->setVrNeto($arProgramacion->getVrNeto() + $vrNeto);
            $em->persist($arProgramacion);
            $em->flush();
        } else {
            $arProgramacionDetalles = $em->getRepository(RhuProgramacionDetalle::class)->findBy(['codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()]);
            if ($arProgramacionDetalles) {
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                    $vrNeto = $em->getRepository(RhuPago::class)->generar($arProgramacionDetalle, $arProgramacion, $arConceptoHora, $arConfiguracion, $arConceptoFondoPension, $usuario);
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
    }
}