<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComEgresoDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComEgresoDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComEgresoDetalle[]    findAll()
 * @method ComEgresoDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComEgresoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComEgresoDetalle::class);
    }

    public function lista($codigoEgreso)
    {
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('ed')
            ->from('App:Compra\ComEgresoDetalle', 'ed')
            ->where("ed.codigoEgresoFk = '{$codigoEgreso}'");

        return $query->getQuery();
    }

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $pago = 0;
        $pagoTotal = 0;
        $floDescuento = 0;
        $floAjustePeso = 0;
        $floRetencionIca = 0;
        $floRetencionIva = 0;
        $floRetencionFuente = 0;
        $arEgreso = $em->getRepository(ComEgreso::class)->find($id);
        $arEgresosDetalle = $em->getRepository(ComEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $id));
        foreach ($arEgresosDetalle as $arEgresoDetalle) {
            $floDescuento += $arEgresoDetalle->getVrDescuento();
            $floAjustePeso += $arEgresoDetalle->getVrAjustePeso();
            $floRetencionIca += $arEgresoDetalle->getVrRetencionIca();
            $floRetencionIva += $arEgresoDetalle->getVrRetencionIva();
            $floRetencionFuente += $arEgresoDetalle->getVrRetencionFuente();
            $pago += $arEgresoDetalle->getVrPago() * $arEgresoDetalle->getOperacion();
            $pagoTotal += $arEgresoDetalle->getVrPagoAfectar();
        }
        $arEgreso->setVrPago($pago);
        $arEgreso->setVrPagoTotal($pagoTotal);
        $arEgreso->setVrTotalDescuento($floDescuento);
        $arEgreso->setVrTotalAjustePeso($floAjustePeso);
        $arEgreso->setVrTotalRetencionIca($floRetencionIca);
        $arEgreso->setVrTotalRetencionIva($floRetencionIva);
        $arEgreso->setVrTotalRetencionFuente($floRetencionFuente);
        $em->persist($arEgreso);
        $em->flush();
        return true;
    }

    /**
     * @param $arEgreso
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arEgreso, $arrDetallesSeleccionados)
    {
        if ($arEgreso->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(ComEgresoDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
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


    public function actualizar($arrControles, $idEgreso)
    {

        $em = $this->getEntityManager();
        $arEgresosDetalle = $em->getRepository('App:Compra\ComEgresoDetalle')->findBy(['codigoEgresoFk' => $idEgreso]);
        foreach ($arEgresosDetalle as $arEgresoDetalle) {
            $intCodigo = $arEgresoDetalle->getCodigoEgresoDetallePk();
            $valorPago = isset($arrControles['TxtVrPago' . $intCodigo]) && $arrControles['TxtVrPago' . $intCodigo] != '' ? $arrControles['TxtVrPago' . $intCodigo] : 0;
            $valorAjustePeso = isset($arrControles['TxtAjustePeso' . $intCodigo]) && $arrControles['TxtAjustePeso' . $intCodigo] != '' ? $arrControles['TxtAjustePeso' . $intCodigo] : 0;
            $valorDescuento = isset($arrControles['TxtVrDescuento' . $intCodigo]) && $arrControles['TxtVrDescuento' . $intCodigo] != '' ? $arrControles['TxtVrDescuento' . $intCodigo] : 0;
            $valorRetencionIva = isset($arrControles['TxtRetencionIva' . $intCodigo]) && $arrControles['TxtRetencionIva' . $intCodigo] != '' ? $arrControles['TxtRetencionIva' . $intCodigo] : 0;
            $valorRetencionIca = isset($arrControles['TxtRetencionIca' . $intCodigo]) && $arrControles['TxtRetencionIca' . $intCodigo] != '' ? $arrControles['TxtRetencionIca' . $intCodigo] : 0;
            $valorRetencionFte = isset($arrControles['TxtRetencionFuente' . $intCodigo]) && $arrControles['TxtRetencionFuente' . $intCodigo] != '' ? $arrControles['TxtRetencionFuente' . $intCodigo] : 0;
            $valorPagoAfectar =
                $valorPago
                - $valorAjustePeso
                - $valorDescuento
                - $valorRetencionIva
                - $valorRetencionIca
                - $valorRetencionFte;
            $arEgresoDetalle->setVrDescuento($valorDescuento);
            $arEgresoDetalle->setVrAjustePeso($valorAjustePeso);
            $arEgresoDetalle->setVrRetencionIca($valorRetencionIca);
            $arEgresoDetalle->setVrRetencionIva($valorRetencionIva);
            $arEgresoDetalle->setVrRetencionFuente($valorRetencionFte);
            $arEgresoDetalle->setVrPago($valorPago);
            $arEgresoDetalle->setVrPagoAfectar($valorPagoAfectar);
            $em->persist($arEgresoDetalle);
        }
        $em->flush();
        $this->liquidar($idEgreso);

    }
}
