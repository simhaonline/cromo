<?php

namespace App\Repository\Tesoreria;


use App\Entity\Financiero\FinCuenta;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesMovimientoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesMovimientoDetalle::class);
    }

    public function lista($codigoMovimiento)
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.numero')
            ->addSelect('md.codigoCuentaPagarFk')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('cp.vrSaldo')
            ->addSelect('md.vrPago')
            ->addSelect('cp.cuenta')
            ->addSelect('md.codigoCuentaFk')
            ->addSelect('md.codigoTerceroFk')
            ->addSelect('md.naturaleza')
            ->addSelect('md.cuenta')
            ->addSelect('md.codigoBancoFk')
            ->addSelect('md.detalle')
            ->leftJoin('md.cuentaPagarRel', 'cp')
            ->leftJoin('md.terceroRel', 't')
            ->where("md.codigoMovimientoFk = '{$codigoMovimiento}'");

        return $queryBuilder;
    }



    /**
     * @param $arMovimiento
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arMovimiento, $arrDetallesSeleccionados)
    {
        if ($arMovimiento->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TesMovimientoDetalle::class)->find($codigo);
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


    public function actualizar($arrControles, $idMovimiento)
    {
        $em = $this->getEntityManager();
        $arrCuenta = $arrControles['arrCuenta'];
        $arrTercero = $arrControles['arrTercero'];
        $arrDetalle = $arrControles['arrDetalle'];
        $arMovimientosDetalle = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $idMovimiento]);
        foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
            $intCodigo = $arMovimientoDetalle->getCodigoMovimientoDetallePk();
            $valorPago = isset($arrControles['TxtVrPago' . $intCodigo]) && $arrControles['TxtVrPago' . $intCodigo] != '' ? $arrControles['TxtVrPago' . $intCodigo] : 0;
            $codigoNaturaleza = isset($arrControles['cboNaturaleza' . $intCodigo]) && $arrControles['cboNaturaleza' . $intCodigo] != '' ? $arrControles['cboNaturaleza' . $intCodigo] : null;
            $detalle = $arrDetalle[$intCodigo];
            if ($arrCuenta[$intCodigo]) {
                $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCuenta[$intCodigo]);
                if ($arCuenta) {
                    $arMovimientoDetalle->setCuentaRel($arCuenta);
                } else {
                    $arMovimientoDetalle->setCuentaRel(null);
                }
            } else {
                $arMovimientoDetalle->setCuentaRel(null);
            }
            if ($arrTercero[$intCodigo]) {
                $arTercero = $em->getRepository(TesTercero::class)->find($arrTercero[$intCodigo]);
                if ($arTercero) {
                    $arMovimientoDetalle->setTerceroRel($arTercero);
                } else {
                    $arMovimientoDetalle->setTerceroRel(null);
                }
            } else {
                $arMovimientoDetalle->setTerceroRel(null);
            }

            $arMovimientoDetalle->setVrPago($valorPago);
            $arMovimientoDetalle->setNaturaleza($codigoNaturaleza);
            $arMovimientoDetalle->setDetalle($detalle);
            $em->persist($arMovimientoDetalle);
        }
        $em->flush();
    }

    public function listaFormato($codigoMovimiento)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimientoDetalle::class, 'md');
        $queryBuilder
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoCuentaPagarFk')
            ->addSelect('md.codigoCuentaFk')
            ->addSelect('md.naturaleza')
            ->addSelect('ter.nombreCorto AS terceroNombreCorto')
            ->addSelect('ter.numeroIdentificacion as terceroNumeroIdentificacion')
            ->addSelect('md.vrPago')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->leftJoin('md.cuentaPagarRel', 'cp')
            ->leftJoin('md.terceroRel', 'ter')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->where('md.codigoMovimientoFk = ' . $codigoMovimiento);
        $queryBuilder->orderBy('md.codigoMovimientoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function listaContabilizar($codigoMovimiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimientoDetalle::class, 'ed');
        $queryBuilder
            ->select('ed.codigoMovimientoDetallePk')
            ->addSelect('ed.vrPago')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('ed.codigoCuentaFk')
            ->addSelect('ed.codigoTerceroFk')
            ->addSelect('ed.naturaleza')
            ->addSelect('ed.detalle')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->where('ed.codigoMovimientoFk = ' . $codigoMovimiento);
        $queryBuilder->orderBy('ed.codigoMovimientoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

}
