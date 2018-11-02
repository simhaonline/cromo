<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
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
     * @param $arProgramacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setCantidadRegistros($arProgramacion){
        $arProgramacion->setCantidad(count($this->_em->getRepository(RhuProgramacionDetalle::class)->findBy(['codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()])));
        $this->_em->persist($arProgramacion);
        $this->_em->flush();
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
            ->andWhere("(c.fechaHasta >= '{$arProgramacion->getFechaDesde()->format('Y-m-d')}')")
            ->orWhere("c.indefinido = 1")->getQuery()->execute();
        /** @var $arContrato RhuContrato */
        foreach ($arContratos as $arContrato) {
            $arProgramacionDetalle = new RhuProgramacionDetalle();
            $arProgramacionDetalle->setProgramacionRel($arProgramacion);
            $arProgramacionDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arProgramacionDetalle->setContratoRel($arContrato);
            $arProgramacionDetalle->setVrSalario($arContrato->getVrSalario());
            $arProgramacionDetalle->setIndefinido($arContrato->getIndefinido());
            $arProgramacionDetalle->setSalarioIntegral($arContrato->getSalarioIntegral());
            $arProgramacionDetalle->setFechaDesde($arProgramacion->getFechaDesde());
            $arProgramacionDetalle->setFechaHasta($arProgramacion->getFechaHasta());
            $arProgramacionDetalle->setSalarioBasico(1);
            if ($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'APR' || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'PRA') {
                $arProgramacionDetalle->setDescuentoPension(0);
                $arProgramacionDetalle->setDescuentoSalud(0);
                $arProgramacionDetalle->setPagoAuxilioTransporte(0);
            }
            $em->persist($arProgramacionDetalle);
            if ($arContrato->getCodigoPensionFk() == 'PEN') {
                $arProgramacionDetalle->setDescuentoPension(0);
            }
            $dateFechaDesde = "";
            $dateFechaHasta = "";
            $intDiasDevolver = 0;
            $fechaFinalizaContrato = $arContrato->getFechaHasta();
            if ($arContrato->getIndefinido() == 1) {
                $fecha = date_create(date('Y-m-d'));
                date_modify($fecha, '+100000 day');
                $fechaFinalizaContrato = $fecha;
            }
            if ($arContrato->getFechaDesde() < $arProgramacion->getFechaDesde() == true) {
                $dateFechaDesde = $arProgramacion->getFechaDesde();
            } else {
                if ($arContrato->getFechaDesde() > $arProgramacion->getFechaHasta() == true) {
                    if ($arContrato->getFechaDesde() == $arProgramacion->getFechaHastaPeriodo()) {
                        $dateFechaDesde = $arProgramacion->getFechaHastaPeriodo();
                        $intDiasDevolver = 1;
                    } else {
                        $intDiasDevolver = 0;
                    }
                } else {
                    $dateFechaDesde = $arContrato->getFechaDesde();
                }
            }

            if ($fechaFinalizaContrato > $arProgramacion->getFechaHasta() == true) {
                $dateFechaHasta = $arProgramacion->getFechaHasta();
            } else {
                if ($fechaFinalizaContrato < $arProgramacion->getFechaDesde() == true) {
                    $intDiasDevolver = 0;
                } else {
                    $dateFechaHasta = $fechaFinalizaContrato;
                }
            }

            $arProgramacionDetalle->setFechaDesde($dateFechaDesde);
            $arProgramacionDetalle->setFechaHasta($dateFechaHasta);
            $arProgramacionDetalle->setFechaDesde($arContrato->getFechaDesde());
            $arProgramacionDetalle->setFechaHasta($dateFechaHasta);
            $arProgramacionDetalle->setDias($intDiasDevolver);
            $arProgramacionDetalle->setDiasReales($intDiasDevolver);
            $horasDiurnas = ($intDiasDevolver * $arContrato->getFactorHorasDia());
            $arProgramacionDetalle->setHorasPeriodo($horasDiurnas);
            $arProgramacionDetalle->setHorasDiurnas($horasDiurnas);
            $arProgramacionDetalle->setHorasPeriodoReales($horasDiurnas);
            $arProgramacionDetalle->setFactorDia($arContrato->getFactorHorasDia());

            $floValorDia = $arContrato->getVrSalario() / 30;
            $floValorHora = $floValorDia / $arContrato->getFactorHorasDia();
            $arProgramacionDetalle->setVrDia($floValorDia);
            $arProgramacionDetalle->setVrHora($floValorHora);
            $floDevengado = $arProgramacionDetalle->getDias() * $floValorDia;
            $arProgramacionDetalle->setVrDevengado($floDevengado);

            $arProgramacionDetalle->setVrCreditos(0);
            $arProgramacionDetalle->setVrDeducciones(0);
            $floNeto = $floDevengado;
            $arProgramacionDetalle->setVrNetoPagar($floNeto);
            $em->persist($arProgramacionDetalle);
        }
        $arProgramacion->setEmpleadosGenerados(0);
        $em->persist($arProgramacion);
        $em->flush();
        $em->getRepository(RhuProgramacion::class)->setCantidadRegistros($arProgramacion);
    }
    /**
     * @param $arProgramacion RhuProgramacion
     */
    public function autorizar($arProgramacion){
        $em = $this->getEntityManager();
        if(!$arProgramacion->getEstadoAutorizado() && $arProgramacion->getEmpleadosGenerados()){
            $arProgramacionDetalles = $em->getRepository(RhuProgramacionDetalle::class)->findBy(['codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()]);
            if($arProgramacionDetalles){
                $em->getRepository(RhuPagoDetalle::class)->eliminarTodoDetalles($arProgramacion->getCodigoProgramacionPk());
                $em->getRepository(RhuPago::class)->eliminarTodo($arProgramacion->getCodigoProgramacionPk());
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                    $em->getRepository(RhuPago::class)->generarPago($arProgramacionDetalle, $arProgramacion);
                }
            }
        }
    }
}