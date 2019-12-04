<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinCuenta;
use App\Entity\General\GenImpuesto;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class CarMovimientoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarMovimientoDetalle::class);
    }

    public function lista($codigoMovimiento)
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(CarMovimientoDetalle::class, 'id')
            ->select('id.codigoMovimientoDetallePk')
            ->addSelect('id.numero')
            ->addSelect('id.codigoCuentaCobrarFk')
            ->addSelect('id.codigoCuentaCobrarTipoFk')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentifiacion')
            ->addSelect('id.vrPago')
            ->addSelect('id.vrRetencion')
            ->addSelect('id.vrBase')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.codigoClienteFk')
            ->addSelect('id.naturaleza')
            ->addSelect('id.detalle')
            ->addSelect('id.codigoImpuestoRetencionFk')
            ->addSelect('id.codigoCentroCostoFk')
            ->leftJoin('id.clienteRel', 'c')
            ->where("id.codigoMovimientoFk = '{$codigoMovimiento}'");
        return $queryBuilder;
    }

    public function eliminar($arMovimiento, $arrDetallesSeleccionados)
    {
        if ($arMovimiento->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(CarMovimientoDetalle::class)->find($codigo);
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

    public function actualizar($arrControles, $id)
    {
        $em = $this->getEntityManager();
        $arrCuenta = $arrControles['arrCuenta'];
        $arrCliente = $arrControles['arrCliente'];
        $arrRetencion = $arrControles['cboImpuestoRetencion'];
        $arrDetalle = $arrControles['arrDetalle'];
        $arrCentroCostos = $arrControles['arrCentroCosto'];
        $arMovimientosDetalle = $em->getRepository(CarMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $id]);
        foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
            $intCodigo = $arMovimientoDetalle->getCodigoMovimientoDetallePk();
            $valorPago = isset($arrControles['TxtVrPago' . $intCodigo]) && $arrControles['TxtVrPago' . $intCodigo] != '' ? $arrControles['TxtVrPago' . $intCodigo] : 0;
            $base = isset($arrControles['TxtVrBase' . $intCodigo]) && $arrControles['TxtVrBase' . $intCodigo] != '' ? $arrControles['TxtVrBase' . $intCodigo] : 0;
            $codigoNaturaleza = isset($arrControles['cboNaturaleza' . $intCodigo]) && $arrControles['cboNaturaleza' . $intCodigo] != '' ? $arrControles['cboNaturaleza' . $intCodigo] : null;
            $codigoRetencion = $arrRetencion[$intCodigo];
            $detalle = $arrDetalle[$intCodigo];
            $retencion = 0;
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
            if ($arrCliente[$intCodigo]) {
                $arCliente = $em->getRepository(CarCliente::class)->find($arrCliente[$intCodigo]);
                if ($arCliente) {
                    $arMovimientoDetalle->setClienteRel($arCliente);
                } else {
                    $arMovimientoDetalle->setClienteRel(null);
                }
            } else {
                $arMovimientoDetalle->setClienteRel(null);
            }
            if ($arrCentroCostos[$intCodigo]){
                $arCentroCostos = $em->getRepository(FinCentroCosto ::class)->find($arrCentroCostos[$intCodigo]);
                if ($arCentroCostos) {
                    $arMovimientoDetalle->setCentroCostoRel($arCentroCostos);
                } else {
                    $arMovimientoDetalle->setCentroCostoRel(null);
                }
            }else{
                $arMovimientoDetalle->setCentroCostoRel(null);
            }
            if($codigoRetencion) {
                if($codigoRetencion != "R00") {
                    /** @var $arImpuesto GenImpuesto*/
                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($codigoRetencion);
                    if($valorPago >= $arImpuesto->getBase()) {
                        $retencion = round($valorPago * $arImpuesto->getPorcentaje() / 100);
                        $base = $valorPago;
                    }
                }
            }
            $arMovimientoDetalle->setVrBase($base);
            $arMovimientoDetalle->setVrPago($valorPago);
            $arMovimientoDetalle->setVrRetencion($retencion);
            $arMovimientoDetalle->setNaturaleza($codigoNaturaleza);
            $arMovimientoDetalle->setCodigoImpuestoRetencionFk($codigoRetencion);
            $arMovimientoDetalle->setDetalle($detalle);
            $em->persist($arMovimientoDetalle);
        }
        $em->flush();
    }

    public function duplicar($arMovimiento, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arMovimiento->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(CarMovimientoDetalle::class)->find($codigo);
                        if ($ar) {
                            $arMovimientoDetalle = new CarMovimientoDetalle();
                            $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                            $arMovimientoDetalle->setCuentaCobrarTipoRel($ar->getCuentaCobrarTipoRel());
                            $arMovimientoDetalle->setNumero($ar->getNumero());
                            $arMovimientoDetalle->setClienteRel($ar->getClienteRel());
                            $arMovimientoDetalle->setNaturaleza('C');
                            $arMovimientoDetalle->setCodigoImpuestoRetencionFk('R00');
                            $em->persist($arMovimientoDetalle);

                        }
                    }
                    try {
                        $em->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listaContabilizar($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarMovimientoDetalle::class, 'id');
        $queryBuilder
            ->select('id.codigoMovimientoDetallePk')
            ->addSelect('id.vrPago')
            ->addSelect('id.vrRetencion')
            ->addSelect('id.vrBase')
            ->addSelect('id.numero')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.naturaleza')
            ->addSelect('id.codigoImpuestoRetencionFk')
            ->addSelect('id.codigoClienteFk')
            ->addSelect('id.codigoCentroCostoFk')
            ->where('id.codigoMovimientoFk = ' . $id);
        $queryBuilder->orderBy('id.codigoMovimientoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function listaFormato($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarMovimientoDetalle::class, 'id');
        $queryBuilder
            ->select('id.codigoMovimientoDetallePk')
            ->addSelect('id.codigoCuentaCobrarFk')
            ->addSelect('id.codigoCuentaCobrarTipoFk')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.naturaleza')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentificacion')
            ->addSelect('id.vrPago')
            ->addSelect('id.numero')
            ->leftJoin('id.clienteRel', 'c')
            ->where('id.codigoMovimientoFk = ' . $id);
        $queryBuilder->orderBy('id.codigoMovimientoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function referencia($codigoCuentaCobrar)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarMovimientoDetalle::class, 'id')
            ->select('id.codigoMovimientoDetallePk')
            ->addSelect('id.vrPago')
            ->addSelect('id.codigoMovimientoFk')
            ->addSelect('i.numero')
            ->leftJoin('id.movimientoRel', 'i')
            ->where('id.codigoCuentaCobrarFk = ' . $codigoCuentaCobrar);
        return $queryBuilder->getQuery()->getResult();
    }

}