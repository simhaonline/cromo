<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurCotizacion;
use App\Entity\Turno\TurCotizacionDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurCotizacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurCotizacionDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCotizacionDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoCotizacionDetallePk')
            ->addSelect('cd.fechaDesde')
            ->addSelect('cd.fechaHasta')
            ->addSelect('cd.cantidad')
            ->addSelect('cd.lunes')
            ->addSelect('cd.martes')
            ->addSelect('cd.miercoles')
            ->addSelect('cd.jueves')
            ->addSelect('cd.viernes')
            ->addSelect('cd.sabado')
            ->addSelect('cd.domingo')
            ->addSelect('cd.festivo')
            ->addSelect('cd.horasProgramadas')
            ->addSelect('cd.horasDiurnasProgramadas')
            ->addSelect('cd.horasNocturnasProgramadas')
            ->addSelect('cd.vrSalarioBase')
            ->addSelect('cd.vrPrecioMinimo')
            ->addSelect('cd.vrPrecioAjustado')
            ->addSelect('cd.porcentajeBaseIva')
            ->addSelect('cd.porcentajeIva')
            ->addSelect('cd.vrIva')
            ->addSelect('cd.vrSubtotal')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('cd.conceptoRel', 'c')
            ->leftJoin('cd.modalidadRel', 'm')
            ->where('cd.codigoCotizacionFk = ' . $id);

        return $queryBuilder;
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arCotizacion TurCotizacion
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arCotizacion)
    {
        /**
         * @var $arCotizacionDetalle TurCotizacionDetalle
         */
        $em = $this->getEntityManager();
        if ($this->getEntityManager()->getRepository(TurCotizacion::class)->contarDetalles($arCotizacion->getCodigoCotizacionPk()) > 0) {
            $arrPrecioAjustado = $arrControles['arrPrecioAjustado'];
//            $arrPorcentajeBaseIva = $arrControles['arrPorcentajeBaseIva'];
            $arrPorcentajeIva = $arrControles['arrPorcentajeIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoCotizacionDetalle) {
                $arCotizacionDetalle = $this->getEntityManager()->getRepository(TurCotizacionDetalle::class)->find($codigoCotizacionDetalle);
                $arCotizacionDetalle->setHoras($arCotizacionDetalle->getConceptoRel()->getHoras());
                $arCotizacionDetalle->setHorasDiurnas($arCotizacionDetalle->getConceptoRel()->getHorasDiurnas());
                $arCotizacionDetalle->setHorasNocturnas($arCotizacionDetalle->getConceptoRel()->getHorasNocturnas());
                $arCotizacionDetalle->setVrSalarioBase($arCotizacionDetalle->getVrSalarioBase());
                $arCotizacionDetalle->setVrPrecioAjustado($arrPrecioAjustado[$codigoCotizacionDetalle]);
                $arCotizacionDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoCotizacionDetalle]);
//                $arCotizacionDetalle->setPorcentajeBaseIva($arrPorcentajeBaseIva[$codigoCotizacionDetalle]);
                $arCotizacionDetalle->setVrSubtotal($arCotizacionDetalle->getVrPrecioAjustado() * $arCotizacionDetalle->getCantidad());
                $arCotizacionDetalle->setVrIva($arCotizacionDetalle->getVrSubtotal() * $arCotizacionDetalle->getPorcentajeIva() / 100);
                $arCotizacionDetalle->setVrTotalDetalle($arCotizacionDetalle->getVrSubtotal() + $arCotizacionDetalle->getVrIva());
                $em->persist($arCotizacionDetalle);
                $em->flush();
            }
            $em->getRepository(TurCotizacion::class)->liquidar($arCotizacion);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arrDetallesSeleccionados
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($id, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(TurCotizacion::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TurCotizacionDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                            $this->getEntityManager()->flush();
                        }
                    }
                    try {
                        $this->getEntityManager()->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

}
