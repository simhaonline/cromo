<?php

namespace App\Repository\Tesoreria;


use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TesMovimientoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
            ->addSelect('md.vrBase')
            ->addSelect('cp.cuenta')
            ->addSelect('md.codigoCuentaFk')
            ->addSelect('md.codigoTerceroFk')
            ->addSelect('md.naturaleza')
            ->addSelect('md.cuenta')
            ->addSelect('md.codigoBancoFk')
            ->addSelect('md.detalle')
            ->addSelect('md.codigoCentroCostoFk')
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
        $arrCentroCosto = $arrControles['arrCentroCosto'];
        $arrTercero = $arrControles['arrTercero'];
        $arrDetalle = $arrControles['arrDetalle'];
        $arrNumero = $arrControles['arrNumero']??null;
        $arMovimientosDetalle = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $idMovimiento]);
        foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
            $intCodigo = $arMovimientoDetalle->getCodigoMovimientoDetallePk();
            $valorPago = isset($arrControles['TxtVrPago' . $intCodigo]) && $arrControles['TxtVrPago' . $intCodigo] != '' ? $arrControles['TxtVrPago' . $intCodigo] : 0;
            $valorBase = isset($arrControles['TxtVrBase' . $intCodigo]) && $arrControles['TxtVrBase' . $intCodigo] != '' ? $arrControles['TxtVrBase' . $intCodigo] : 0;
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
            if ($arrCentroCosto[$intCodigo]) {
                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arrCentroCosto[$intCodigo]);
                if ($arCentroCosto) {
                    $arMovimientoDetalle->setCentroCostoRel($arCentroCosto);
                } else {
                    $arMovimientoDetalle->setCentroCostoRel(null);
                }
            } else {
                $arMovimientoDetalle->setCentroCostoRel(null);
            }
            $arMovimientoDetalle->setVrBase($valorBase);
            $arMovimientoDetalle->setVrPago($valorPago);
            $arMovimientoDetalle->setNaturaleza($codigoNaturaleza);
            $arMovimientoDetalle->setDetalle($detalle);
            if($arrNumero) {
                $numero = $arrNumero[$intCodigo] && $arrNumero[$intCodigo] != '' ? $arrNumero[$intCodigo] : null;
                $arMovimientoDetalle->setNumero($numero);
            }

            $em->persist($arMovimientoDetalle);
        }
        $em->flush();
    }

    public function listaFormato($codigoMovimiento)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoCuentaPagarFk')
            ->addSelect('md.codigoCuentaFk')
            ->addSelect('md.naturaleza')
            ->addSelect('ter.nombreCorto AS terceroNombreCorto')
            ->addSelect('ter.numeroIdentificacion as terceroNumeroIdentificacion')
            ->addSelect('md.vrPago')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('b.nombre as banco')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->leftJoin('md.cuentaPagarRel', 'cp')
            ->leftJoin('md.terceroRel', 'ter')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->leftJoin('md.bancoRel', 'b')
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
            ->addSelect('ed.codigoMovimientoFk')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->where('ed.codigoMovimientoFk = ' . $codigoMovimiento);
        $queryBuilder->orderBy('ed.codigoMovimientoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function referencia($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoMovimientoFk')
            ->addSelect('md.vrPago')
            ->addSelect('m.numero')
            ->leftJoin('md.movimientoRel', 'm')
            ->where('md.codigoCuentaPagarFk = ' . $codigo);
        return $queryBuilder->getQuery()->getResult();
    }

    public function listaImprimirAgrupado($codigoMovimiento)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT t.numeroIdentificacion, 
                       t.codigoIdentificacionFk,
                       t.codigoCuentaTipoFk,
                       t.nombreCorto,
                       b.codigoBancolombia,
                       b.codigoInterface,
                       pbd.cuenta,
                       SUM(pbd.vrPago) AS vrPago 
                       FROM App\Entity\Tesoreria\TesMovimientoDetalle pbd JOIN pbd.terceroRel t JOIN t.bancoRel b 
                       WHERE pbd.codigoMovimientoFk = {$codigoMovimiento}";
        $dql .= " GROUP BY   t.numeroIdentificacion,   
                             t.codigoIdentificacionFk,
                             t.codigoCuentaTipoFk,
                             t.nombreCorto,
                             b.codigoBancolombia,
                             b.codigoInterface,
                             pbd.cuenta";
        $dql .= " ORDER  BY  t.numeroIdentificacion DESC";
        $query = $em->createQuery($dql);
        $arMovimientoDetalles = $query->getResult();
        return $arMovimientoDetalles;

    }
}
