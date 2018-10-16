<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinTercero;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoAsiento)
    {
        $em = $this->getEntityManager();
        $arAsiento = $em->getRepository(FinAsiento::class)->find($codigoAsiento);
        $arAsientoDetalles = $em->getRepository(FinAsientoDetalle::class)->findBy(['codigoAsientoFk' => $codigoAsiento]);
        $debitoGeneral = 0;
        $creditoGeneral = 0;
        foreach ($arAsientoDetalles as $arAsientoDetalle) {
            //$arAsientoDetalleAct = $em->getRepository(FinAsientoDetalle::class)->find($arAsientoDetalle->getCodigoAsientoDetallePk());
            $debitoGeneral += $arAsientoDetalle->getVrDebito();
            $creditoGeneral += $arAsientoDetalle->getVrCredito();
            //$arAsientoDetalleAct->setVrIva($iva);
            //$arAsientoDetalleAct->setVrTotal($total);
            //$em->persist($arAsientoDetalleAct);
        }
        $arAsiento->setVrDebito($debitoGeneral);
        $arAsiento->setVrCredito($creditoGeneral);
        $em->persist($arAsiento);
        $em->flush();
    }

    /**
     * @param $arPedido InvPedido
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arAsiento)
    {
        if(!$arAsiento->getEstadoAutorizado()) {
            $registros = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class,'ad')
                ->select('COUNT(ad.codigoAsientoDetallePk) AS registros')
                ->where('ad.codigoAsientoFk = ' . $arAsiento->getCodigoAsientoPk())
                ->getQuery()->getSingleResult();
            if($registros['registros'] > 0) {
                $arAsiento->setEstadoAutorizado(1);
                $this->getEntityManager()->persist($arAsiento);
                $this->getEntityManager()->flush();
            } else {
                Mensajes::error("El registro no tiene detalles");
            }
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    /**
     * @param $arPedido InvPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arAsiento)
    {
        if($arAsiento->getEstadoAutorizado()) {
            $arAsiento->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arAsiento);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $codigoAsiento
     * @param $arrControles
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($codigoAsiento, $arrControles){
        $em = $this->getEntityManager();
        if($this->contarDetalles($codigoAsiento) > 0){
            $arrCuenta = $arrControles['arrCuenta'];
            $arrTercero = $arrControles['arrTercero'];
            $arrCodigo = $arrControles['TxtCodigo'];
            $arrDebito = $arrControles['TxtDebito'];
            $arrCredito = $arrControles['TxtCredito'];
            foreach ($arrCodigo as $codigo) {
                $arAsientoDetalle = $em->getRepository(FinAsientoDetalle::class)->find($codigo);
                if($arrTercero[$codigo]) {
                    $arTercero = $em->getRepository(FinTercero::class)->find($arrTercero[$codigo]);
                    if($arTercero) {
                        $arAsientoDetalle->setTerceroRel( $arTercero);
                    } else {
                        $arAsientoDetalle->setTerceroRel(null);
                    }
                } else {
                    $arAsientoDetalle->setTerceroRel(null);
                }

                if($arrCuenta[$codigo]) {
                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCuenta[$codigo]);
                    if($arCuenta) {
                        $arAsientoDetalle->setCuentaRel( $arCuenta);
                    } else {
                        $arAsientoDetalle->setCuentaRel(null);
                    }
                } else {
                    $arAsientoDetalle->setCuentaRel(null);
                }
                $arAsientoDetalle->setVrDebito( $arrDebito[$codigo] != '' ? $arrDebito[$codigo] : 0);
                $arAsientoDetalle->setVrCredito( $arrCredito[$codigo] != '' ? $arrCredito[$codigo] : 0);
                $em->persist($arAsientoDetalle);
            }
        }
        $em->flush();
        $this->liquidar($codigoAsiento);
    }

    /**
     * @param $codigo
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoAsiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
            ->select("COUNT(ad.codigoAsientoDetallePk)")
            ->where("ad.codigoAsientoFk = {$codigoAsiento} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

}