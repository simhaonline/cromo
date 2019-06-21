<?php

namespace App\Repository\Turno;


use App\Entity\Crm\CrmVisita;
use App\Entity\General\GenImpuesto;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TurFacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurFactura::class);
    }

    public function liquidar($arFactura)
    {
        /**
         * @var $arFactura TurFactura
         */
        $em = $this->getEntityManager();
        $respuesta = '';
        $retencionFuente = $arFactura->getClienteRel()->getRetencionFuente();
        $retencionFuenteSinBase = $arFactura->getClienteRel()->getRetencionFuenteSinBase();
        $vrTotalBrutoGlobal = 0;
        $vrTotalGlobal = 0;
        $vrTotalNetoGlobal = 0;
        $vrDescuentoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $vrRetencionFuenteGlobal = 0;
        $vrRetencionIvaGlobal = 0;
        $vrAutoretencion = 0;
        $arFacturaDetalles = $this->getEntityManager()->getRepository(TurFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]);
        $arrImpuestoRetenciones = $this->retencion($arFacturaDetalles, $retencionFuenteSinBase);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $vrPrecio = $arFacturaDetalle->getVrPrecio();
            $vrSubtotal = $vrPrecio * $arFacturaDetalle->getCantidad();
            $vrDescuento = ($arFacturaDetalle->getVrPrecio() * $arFacturaDetalle->getCantidad()) - $vrSubtotal;
            $vrIva = ($vrSubtotal * ($arFacturaDetalle->getPorcentajeIva()) / 100);
            $vrTotalBruto = $vrSubtotal;
            $vrTotal = $vrTotalBruto + $vrIva;
            $vrRetencionFuente = 0;
            $vrTotalGlobal += $vrTotal;
            $vrTotalBrutoGlobal += $vrTotalBruto;
            $vrDescuentoGlobal += $vrDescuento;
            $vrIvaGlobal += $vrIva;
            $vrSubtotalGlobal += $vrSubtotal;
            if ($arFacturaDetalle->getCodigoImpuestoRetencionFk()) {
                if ($retencionFuente) {
                    if ($arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['base'] == true || $retencionFuenteSinBase) {
                        $vrRetencionFuente = $vrSubtotal * $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['porcentaje'] / 100;
                    }
                }
            }
            $vrRetencionFuenteGlobal += $vrRetencionFuente;
            $arFacturaDetalle->setVrSubtotal($vrSubtotal);
            $arFacturaDetalle->setVrIva($vrIva);
            $arFacturaDetalle->setVrTotal($vrTotal);
            $arFacturaDetalle->setVrRetencionFuente($vrRetencionFuente);
            $this->getEntityManager()->persist($arFacturaDetalle);
        }

        $vrTotalNetoGlobal = $vrTotalGlobal - $vrRetencionFuenteGlobal - $vrRetencionIvaGlobal;
        $arFactura->setVrIva($vrIvaGlobal);
        $arFactura->setVrSubtotal($vrSubtotalGlobal);
        $arFactura->setVrTotal($vrTotalGlobal);
        $arFactura->setVrNeto($vrTotalNetoGlobal);
        $arFactura->setVrDescuento($vrDescuentoGlobal);
        $arFactura->setVrRetencionFuente($vrRetencionFuenteGlobal);
        $arFactura->setVrRetencionIva($vrRetencionIvaGlobal);
        $arFactura->setVrAutoretencion($vrAutoretencion);
        $this->getEntityManager()->persist($arFactura);
        if ($respuesta == '') {
            $em->flush();
        } else {
            Mensajes::error($respuesta);
        }
    }

    private function retencion($arFacturaDetalles, $retencionFuenteSinBase)
    {
        $em = $this->getEntityManager();
        $arrImpuestoRetenciones = array();
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            if ($arFacturaDetalle->getCodigoImpuestoRetencionFk()) {
                $vrPrecio = $arFacturaDetalle->getVrPrecio();
                $vrSubtotal = $vrPrecio * $arFacturaDetalle->getCantidad();
                if (!array_key_exists($arFacturaDetalle->getCodigoImpuestoRetencionFk(), $arrImpuestoRetenciones)) {
                    $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()] = array('codigo' => $arFacturaDetalle->getCodigoImpuestoRetencionFk(),
                        'valor' => $vrSubtotal, 'base' => false, 'porcentaje' => 0);
                } else {
                    $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['valor'] += $vrSubtotal;
                }
            }
        }

        if ($arrImpuestoRetenciones) {
            foreach ($arrImpuestoRetenciones as $arrImpuestoRetencion) {
                $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrImpuestoRetencion['codigo']);
                if ($arImpuesto) {
                    if ($arrImpuestoRetencion['valor'] >= $arImpuesto->getBase() || $retencionFuenteSinBase) {
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['base'] = true;
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['porcentaje'] = $arImpuesto->getPorcentaje();
                    }
                }
            }
        }
        return $arrImpuestoRetenciones;
    }

    public function autorizar($arFactura)
    {
        $em = $this->getEntityManager();
        if ($em->getRepository(TurFacturaDetalle::class)->contarDetalles($arFactura->getCodigoFacturaPk()) > 0) {
            $arFactura->setEstadoAutorizado(1);
            $em->persist($arFactura);
            $em->flush();
        } else {
            Mensajes::error('La factura no contiene detalles');
        }
    }

    public function desautorizar($arFactura)
    {
        $em = $this->getEntityManager();
        if ($arFactura->getEstadoAutorizado()) {
            $arFactura->setEstadoAutorizado(0);
            $em->persist($arFactura);
            $em->flush();

        } else {
            Mensajes::error('El registro no esta autorizado');
        }
    }

    public function aprobar($arFactura){
        $em = $this->getEntityManager();
        if($arFactura->getEstadoAutorizado() == 1 ) {
            if($arFactura->getEstadoAprobado() == 0){
                $arFactura->setEstadoAprobado(1);
                $em->persist($arFactura);
                $em->flush();
            }else{
                Mensajes::error('La factura ya esta aprobada');
            }

        } else {
            Mensajes::error('La factura ya esta desautorizada');
        }
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TurFactura::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arRegistro->getCodigoFacturaPk()])) <= 0) {
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
