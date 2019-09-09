<?php

namespace App\Repository\Tesoreria;


use App\Entity\Tesoreria\TesEgreso;
use App\Entity\Tesoreria\TesEgresoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesEgresoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesEgresoDetalle::class);
    }

    public function lista($codigoEgreso)
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesEgresoDetalle::class, 'ed')
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.numero')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('cp.vrSaldo')
            ->addSelect('ed.vrPagoAfectar')
            ->addSelect('ed.vrPagoAfectar')
            ->addSelect('ed.vrPago')
            ->addSelect('cp.cuenta')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->leftJoin('cp.terceroRel', 't')
            ->where("ed.codigoEgresoFk = '{$codigoEgreso}'");

        return $queryBuilder;
    }

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $pago = 0;
        $pagoTotal = 0;
        $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
        $arEgresosDetalle = $em->getRepository(TesEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $id));
        foreach ($arEgresosDetalle as $arEgresoDetalle) {
            $pago += $arEgresoDetalle->getVrPago();
            $pagoTotal += $arEgresoDetalle->getVrPagoAfectar();
        }
        $arEgreso->setVrPago($pago);
        $arEgreso->setVrPagoTotal($pagoTotal);
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
                        $ar = $this->getEntityManager()->getRepository(TesEgresoDetalle::class)->find($codigo);
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
        $arEgresosDetalle = $em->getRepository(TesEgresoDetalle::class)->findBy(['codigoEgresoFk' => $idEgreso]);
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
                + $valorAjustePeso
                - $valorDescuento
                - $valorRetencionIva
                - $valorRetencionIca
                - $valorRetencionFte;
            //$arEgresoDetalle->setVrDescuento($valorDescuento);
            //$arEgresoDetalle->setVrAjustePeso($valorAjustePeso);
            //$arEgresoDetalle->setVrRetencionIca($valorRetencionIca);
            //$arEgresoDetalle->setVrRetencionIva($valorRetencionIva);
            //$arEgresoDetalle->setVrRetencionFuente($valorRetencionFte);
            $arEgresoDetalle->setVrPago($valorPagoAfectar);
            $arEgresoDetalle->setVrPagoAfectar($valorPago);
            $em->persist($arEgresoDetalle);
        }
        $em->flush();
        $this->liquidar($idEgreso);

    }

    public function listaFormato($codigoEgreso)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesEgresoDetalle::class, 'ed');
        $queryBuilder
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.codigoCuentaPagarFk')
            ->addSelect('ter.nombreCorto AS tercero')
            ->addSelect('ter.numeroIdentificacion')
            ->addSelect('cp.fecha')
            ->addSelect('ed.vrPagoAfectar')
            ->leftJoin('ed.egresoRel', 'r')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->leftJoin('cp.terceroRel', 'ter')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->where('ed.codigoEgresoFk = ' . $codigoEgreso);
        $queryBuilder->orderBy('ed.codigoEgresoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function listaContabilizar($codigoEgreso)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesEgresoDetalle::class, 'ed');
        $queryBuilder
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.vrPago')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cpt.codigoCuentaProveedorFk')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->where('ed.codigoEgresoFk = ' . $codigoEgreso);
        $queryBuilder->orderBy('ed.codigoEgresoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

}
