<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPedidoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPedidoDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.codigoContratoDetalleFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.modalidadRel', 'm')
            ->where('pd.codigoPedidoFk = ' . $id);

        return $queryBuilder;
    }

    public function pendienteProgramar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.codigoContratoDetalleFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.modalidadRel', 'm')
            ->where('pd.estadoProgramado = 0');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arPedido
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arPedido)
    {
        $em = $this->getEntityManager();
        if ($this->getEntityManager()->getRepository(TurPedido::class)->contarDetalles($arPedido->getCodigoPedidoPk()) > 0) {
            $arrPrecioAjustado = $arrControles['arrPrecioAjustado'];
            $arrPorcentajeBaseIva = $arrControles['arrPorcentajeBaseIva'];
            $arrPorcentajeIva = $arrControles['arrPorcentajeIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoPedidoDetalle) {
                $arPedidoDetalle = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
                $arPedidoDetalle->setHoras($arPedidoDetalle->getConceptoRel()->getHoras());
                $arPedidoDetalle->setHorasDiurnas($arPedidoDetalle->getConceptoRel()->getHorasDiurnas());
                $arPedidoDetalle->setHorasNocturnas($arPedidoDetalle->getConceptoRel()->getHorasNocturnas());
                $arPedidoDetalle->setVrSalarioBase($arPedidoDetalle->getVrSalarioBase());
                $arPedidoDetalle->setVrPrecioAjustado($arrPrecioAjustado[$codigoPedidoDetalle]);
                $arPedidoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoPedidoDetalle]);
                $arPedidoDetalle->setPorcentajeBaseIva($arrPorcentajeBaseIva[$codigoPedidoDetalle]);
                $arPedidoDetalle->setVrSubtotal($arPedidoDetalle->getVrPrecioAjustado() * $arPedidoDetalle->getCantidad());
                $arPedidoDetalle->setVrIva($arPedidoDetalle->getVrSubtotal() * $arPedidoDetalle->getPorcentajeIva() / 100);
                $arPedidoDetalle->setVrTotalDetalle($arPedidoDetalle->getVrSubtotal() + $arPedidoDetalle->getVrIva());
                $em->persist($arPedidoDetalle);
                $em->flush();
            }
            $em->getRepository(TurPedido::class)->liquidar($arPedido);
            $em->flush();
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
        $arRegistro = $em->getRepository(TurPedido::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->find($codigo);
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

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.modalidadRel', 'm');

        return $queryBuilder;
    }

}
