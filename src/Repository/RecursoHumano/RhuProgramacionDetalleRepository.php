<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
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
            ->addSelect('pd.fechaDesde')
            ->addSelect('pd.fechaHasta')
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
            ->leftJoin('pd.empleadoRel','e')
            ->where("pd.codigoProgramacionFk = {$id}")->getQuery()->execute();
    }
}