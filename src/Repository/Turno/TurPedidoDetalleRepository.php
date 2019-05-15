<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
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
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.cantidad')
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
            ->addSelect('coc.nombre as nombreConcepto')
            ->addSelect('com.nombre as nombreModalidad')
            ->leftJoin('pd.contratoConceptoRel', 'coc')
            ->leftJoin('pd.contratoModalidadRel', 'com')
            ->where('pd.codigoPedidoFk = ' . $id);

        return $queryBuilder;
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
                $arPedidoDetalle->setHoras($arPedidoDetalle->getContratoConceptoRel()->getHoras());
                $arPedidoDetalle->setHorasDiurnas($arPedidoDetalle->getContratoConceptoRel()->getHorasDiurnas());
                $arPedidoDetalle->setHorasNocturnas($arPedidoDetalle->getContratoConceptoRel()->getHorasNocturnas());
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
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arPedido
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arPedido, $arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoPedidoDetalle) {
                $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
                if ($arPedidoDetalle) {
                    $em->remove($arPedidoDetalle);
                }
            }
            $em->flush();
        }
    }

}
