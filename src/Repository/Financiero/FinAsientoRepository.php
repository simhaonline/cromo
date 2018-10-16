<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinAsientoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinAsiento::class);
    }

    /**
     * @param $codigoAsiento
     * @param $arrControles
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($codigo, $arrControles){
        $em = $this->getEntityManager();
        if($this->contarDetalles($codigo) > 0){
            $arrCantidad = $arrControles['TxtCantidad'];
            $arrPrecio = $arrControles['TxtPrecio'];
            $arrCodigo = $arrControles['TxtCodigo'];
            foreach ($arrCodigo as $codigo) {
                $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($codigo);
                $arPedidoDetalle->setCantidad( $arrCantidad[$codigo] != '' ? $arrCantidad[$codigo] :0 );
                $arPedidoDetalle->setVrPrecio( $arrPrecio[$codigo] != '' ? $arrPrecio[$codigo] : 0);
                $em->persist($arPedidoDetalle);
            }
        }
        $em->flush();
        $this->liquidar($codigoPedido);
    }

    /**
     * @param $codigo
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
            ->select("COUNT(ad.codigoAsientoDetallePk)")
            ->where("ad.codigoAsientoFk = {$codigo} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

}