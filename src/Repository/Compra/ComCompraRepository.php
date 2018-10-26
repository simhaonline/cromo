<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCompra;
use App\Entity\Compra\ComCompraDetalle;
use App\Entity\Compra\ComCompraTipo;
use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComCuentaPagarTipo;
use App\Entity\Compra\ComProveedor;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComCompra|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComCompra|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComCompra[]    findAll()
 * @method ComCompra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComCompraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComCompra::class);
    }


    /**
     * @param $arCompra ComCompra
     * @param $arrValor
     * @param $arrCantidad
     * @param $arrIva
     * @param $arrDescuento
     */
    public function actualizar($arCompra, $arrValor, $arrCantidad, $arrIva, $arrDescuento)
    {
        $vrTotalGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $vrDctoGlobal = 0;
        $arCompraDetalles = $this->_em->getRepository(ComCompraDetalle::class)->findBy(['codigoCompraFk' => $arCompra->getCodigoCompraPk()]);
        if (count($arCompraDetalles) > 0) {
            foreach ($arCompraDetalles as $arCompraDetalle) {
                $id = $arCompraDetalle->getCodigoCompraDetallePk();
                $cantidad = $arrCantidad[$id] != '' ? $arrCantidad[$id] : 0;
                $vrUnitario = $arrValor[$id] != '' ? $arrValor[$id] : 0;
                $porDcto = $arrDescuento[$id] != '' ? $arrDescuento[$id] : 0;
                $porIva = $arrIva[$id] != '' ? $arrIva[$id] : 0;

                $vrSubtotal = $vrUnitario * $cantidad;
                $vrIva = $vrSubtotal * ($porIva / 100);
                $vrDcto = $vrSubtotal * ($porDcto / 100);
                $vrTotal = $vrSubtotal + $vrIva - $vrDcto;

                $vrTotalGlobal += $vrTotal;
                $vrIvaGlobal += $vrIva;
                $vrSubtotalGlobal += $vrSubtotal;
                $vrDctoGlobal += $vrDcto;

                $arCompraDetalle->setPorIva($porIva);
                $arCompraDetalle->setPorDescuento($porDcto);
                $arCompraDetalle->setVrDescuento($vrDcto);
                $arCompraDetalle->setCantidad($cantidad);
                $arCompraDetalle->setVrPrecioUnitario($vrUnitario);
                $arCompraDetalle->setVrSubtotal($vrSubtotal);
                $arCompraDetalle->setVrIva($vrIva);
                $arCompraDetalle->setVrTotal($vrTotal);
                $this->_em->persist($arCompraDetalle);
            }

            $arCompra->setVrDescuento($vrDctoGlobal);
            $arCompra->setVrIva($vrIvaGlobal);
            $arCompra->setVrSubtotal($vrSubtotalGlobal);
            $arCompra->setVrTotal($vrTotalGlobal);
            $this->_em->persist($arCompraDetalle);
        } else {
            $arCompra->setVrDescuento(0);
            $arCompra->setVrIva(0);
            $arCompra->setVrSubtotal(0);
            $arCompra->setVrTotal(0);
            $this->_em->persist($arCompra);
        }
        $this->_em->flush();
    }

    /**
     * @param $arCompra ComCompra
     */
    public function autorizar($arCompra)
    {
        if (count($this->_em->getRepository(ComCompraDetalle::class)->findBy(['codigoCompraFk' => $arCompra->getCodigoCompraPk()])) > 0) {
            $arCompra->setEstadoAutorizado(1);
            $this->_em->persist($arCompra);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arCompra ComCompra
     */
    public function desautorizar($arCompra)
    {
        if ($arCompra->getEstadoAutorizado() == 1 && $arCompra->getEstadoAprobado() == 0) {
            $arCompra->setEstadoAutorizado(0);
            $this->_em->persist($arCompra);
            $this->_em->flush();
        } else {
            Mensajes::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @var $arCompra ComCompra
     * @throws \Doctrine\ORM\ORMException
     */
    public function aprobar($arCompra)
    {
        $em = $this->getEntityManager();
        $arCompraTipo = $this->_em->getRepository(ComCompraTipo::class)->find($arCompra->getCodigoCompraTipoFk());
        if (!$arCompra->getEstadoAprobado()) {
            $arCompraTipo->setConsecutivo($arCompraTipo->getConsecutivo() + 1);
            $arCompra->setEstadoAprobado(1);
            $arCompra->setNumeroCompra($arCompraTipo->getConsecutivo());
            $arProveedor = $em->getRepository(ComProveedor::class)->findOneBy(['codigoProveedorPk' => $arCompra->getProveedorRel()->getCodigoProveedorPk()]);
            $arCuentaPagarTipo = $em->getRepository(ComCuentaPagarTipo::class)->find($arCompra->getCompraTipoRel()->getCodigoCuentaPagarTipoFk());
            $arCuentaPagar = new ComCuentaPagar();
            $arCuentaPagar->setProveedorRel($arProveedor);
            $arCuentaPagar->setCuentaPagarTipoRel($arCuentaPagarTipo);
            $arCuentaPagar->setFechaFactura($arCompra->getFechaFactura());
            $arCuentaPagar->setFechaVence($arCompra->getFechaVencimiento());
            $arCuentaPagar->setModulo("COM");
            $arCuentaPagar->setCodigoDocumento($arCompra->getCodigoCompraPk());
            $arCuentaPagar->setNumeroDocumento($arCompra->getNumeroCompra());
            $arCuentaPagar->setSoporte($arCompra->getSoporte());
            $arCuentaPagar->setVrSubtotal($arCompra->getVrSubtotal());
            $arCuentaPagar->setVrIva($arCompra->getVrIva());
            $arCuentaPagar->setVrTotal($arCompra->getVrTotal());
            $arCuentaPagar->setVrRetencionFuente($arCompra->getVrRetencion());
            $arCuentaPagar->setVrRetencionIva($arCompra->getVrRetencionIva());
            $arCuentaPagar->setVrSaldo($arCompra->getVrTotal());
            $arCuentaPagar->setVrSaldoOperado($arCompra->getVrTotal() * $arCuentaPagarTipo->getOperacion());
            $arCuentaPagar->setOperacion($arCuentaPagarTipo->getOperacion());
            $em->persist($arCuentaPagar);

            $this->_em->persist($arCompraTipo);
            $this->_em->persist($arCompra);
            $this->_em->flush();
        }
    }

    /**
     * @param $arCompra ComCompra
     * @return array
     */
    public function anular($arCompra)
    {
        $respuesta = [];
        if ($arCompra->getEstadoAprobado() == 1) {
            $arCompra->setEstadoAnulado(1);
            $this->_em->persist($arCompra);
            if (count($respuesta) == 0) {
                $this->_em->flush();
            }
            return $respuesta;
        }
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arCompra ComCompra
         */
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(ComCompra::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(ComCompraDetalle::class)->findBy(['codigoCompraFk' => $arRegistro->getCodigoCompraPk()])) <= 0) {
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

}
