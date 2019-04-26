<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuProgramacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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
    public function eliminar($arrSeleccionados, $arProgramacion){
        $em = $this->getEntityManager();
        foreach ($arrSeleccionados as $codigoProgramacionDetalle){
            $arProgramacionDetalle = $em->getRepository(RhuProgramacionDetalle::class)->find($codigoProgramacionDetalle);
            if($arProgramacionDetalle){
                $em->remove($arProgramacionDetalle);
            }
        }
        $cantidad = $em->getRepository(RhuProgramacion::class)->getCantidadRegistros($arProgramacion->getCodigoProgramacionPk());
        $arProgramacion->setCantidad($cantidad);
        $em->persist($arProgramacion);
        $em->flush();
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

    public function lista($id){
        return $this->_em->createQueryBuilder()->from(RhuProgramacionDetalle::class,'pd')
            ->select('pd.codigoProgramacionDetallePk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('pd.codigoContratoFk')
            ->addSelect('pd.fechaDesdeContrato')
            ->addSelect('pd.fechaHastaContrato')
            ->addSelect('pd.vrSalario')
            ->addSelect('pd.vrNeto')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasFestivasDiurnas')
            ->addSelect('pd.horasFestivasNocturnas')
            ->addSelect('pd.horasExtrasOrdinariasDiurnas')
            ->addSelect('pd.horasExtrasOrdinariasNocturnas')
            ->addSelect('pd.horasExtrasFestivasDiurnas')
            ->addSelect('pd.horasExtrasFestivasNocturnas')
            ->addSelect('pd.horasRecargoNocturno')
            ->addSelect('pd.horasRecargoFestivoDiurno')
            ->addSelect('pd.horasRecargoFestivoNocturno')
            ->addSelect('pd.codigoEmpleadoFk')
            ->leftJoin('pd.empleadoRel','e')
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
        $em->persist($arProgramacionDetalle);
        $em->flush();
        $em->getRepository(RhuProgramacion::class)->generar($arProgramacion, $arProgramacionDetalle->getCodigoProgramacionDetallePk(), $usuario);
    }

    public function exportar($id)
    {

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuProgramacionDetalle::class, 'pd')
            ->select('pd.codigoProgramacionDetallePk')
            ->addSelect('pd.codigoEmpleadoFk as CODEMP')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('pd.codigoContratoFk')
            ->addSelect('pd.fechaDesdeContrato as desde')
            ->addSelect('pd.fechaHastaContrato as hasta')
            ->addSelect('pd.vrSalario')
            ->addSelect('pd.vrNeto')
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

        return $queryBuilder;
    }
}