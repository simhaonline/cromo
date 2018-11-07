<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\Seguridad\Usuario;
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
    public function setCantidadRegistros($arProgramacion)
    {
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
            $fechaDesde = $this->fechaDesdeContrato($arProgramacion->getFechaDesde(), $arContrato->getFechaDesde());
            $fechaHasta = $this->fechaHastaContrato($arProgramacion->getFechaHasta(), $arContrato->getFechaHasta(), $arContrato->getIndefinido());
            $dias = $fechaDesde->diff($fechaHasta)->days + 1;
            $horas = $dias * $arContrato->getFactorHorasDia();
            $arProgramacionDetalle->setFechaDesde($fechaDesde);
            $arProgramacionDetalle->setFechaHasta($fechaHasta);
            $arProgramacionDetalle->setDias($dias);
            $arProgramacionDetalle->setHorasDiurnas($horas);
            $em->persist($arProgramacionDetalle);
        }
        $arProgramacion->setEmpleadosGenerados(0);
        $em->persist($arProgramacion);
        $em->flush();
        $em->getRepository(RhuProgramacion::class)->setCantidadRegistros($arProgramacion);
    }

    /**
     * @param $arProgramacion RhuProgramacion
     * @param $usuario Usuario
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arProgramacion, $usuario)
    {
        $em = $this->getEntityManager();
        $douNetoTotal = 0;
        $numeroPagos = 0;
        if (!$arProgramacion->getEstadoAutorizado()) {
            $arProgramacionDetalles = $em->getRepository(RhuProgramacionDetalle::class)->findBy(['codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()]);
            if ($arProgramacionDetalles) {
                $arConceptoHora = $em->getRepository(RhuConceptoHora::class)->findAll();
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                    $vrNeto = $em->getRepository(RhuPago::class)->generar($arProgramacionDetalle, $arProgramacion, $arConceptoHora, $usuario);
                    $douNetoTotal += $vrNeto;
                    $numeroPagos++;
                }
                $arProgramacion->setVrNeto($douNetoTotal);
                $em->persist($arProgramacion);
                $em->flush();
            }
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
}