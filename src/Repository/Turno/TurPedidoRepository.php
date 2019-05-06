<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TurPedidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPedido::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TurPedido::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurPedidoDetalle::class)->findBy(['codigoPedidoFk' => $arRegistro->getCodigoPedidoPk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    /**
     * @param $codigoPedido
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoPedido)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select("COUNT(pd.codigoPedidoDetallePk)")
            ->where("pd.codigoPedidoFk = {$codigoPedido} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }


    /**
     * @param $arPedido TurPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($arPedido)
    {
        $em = $this->getEntityManager();
        $vrSubtotalGlobal = 0;
        $vrTotalBrutoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSalarioBaseGlobal = 0;
        $arPedidoDetalles = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->findBy(['codigoPedidoFk' => $arPedido->getCodigoPedidoPk()]);

        foreach ($arPedidoDetalles as $arPedidoDetalle) {
            $vrSubtotal = $arPedidoDetalle->getVrSubtotal();
            $vrSubtotalGlobal += $vrSubtotal;
            $vrTotal = $arPedidoDetalle->getVrTotalDetalle();
            $vrTotalBrutoGlobal += $vrTotal;
            $vrIva = $arPedidoDetalle->getVrIva();
            $vrIvaGlobal += $vrIva;
            $vrSalarioBase = $arPedidoDetalle->getVrSalarioBase();
            $vrSalarioBaseGlobal += $vrSalarioBase;

        }
        $arPedido->setVrSubtotal($vrSubtotalGlobal);
        $arPedido->setVrTotal($vrTotalBrutoGlobal);
        $arPedido->setVrIva($vrIvaGlobal);
        $arPedido->setVrTotalServicio($vrTotalBrutoGlobal);
        $arPedido->setVrSalarioBase($vrSalarioBaseGlobal);

        $em->persist($arPedido);
        $em->flush();
    }
}
