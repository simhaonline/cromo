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
use Symfony\Component\HttpFoundation\Session\Session;

class TurFacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurFactura::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoClienteFk = null;
        $numero = null;
        $codigoFacturaPk = null;
        $codigoFacturaTipoFk = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;


        if ($filtros) {
            $codigoFacturaPk = $filtros['codigoFacturaPk'] ?? null;
            $codigoClienteFk = $filtros['codigoClienteFk'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $codigoFacturaTipoFk = $filtros['codigoPedidoTipoFk'] ?? null;
            $fechaDesde = $filtros['$fechaDesde'] ?? null;
            $fechaHasta = $filtros['$fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('c.nombreCorto as cliente')
            ->addSelect('f.numero')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrIva')
            ->addSelect('f.vrNeto')
            ->addSelect('f.vrTotal')
            ->addSelect('f.usuario')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAnulado')
            ->leftJoin('f.clienteRel', 'c');

        if ($codigoFacturaPk) {
            $queryBuilder->andWhere("f.codigoFacturaPk = {$codigoFacturaPk}");
        }
        if ($codigoClienteFk) {
            $queryBuilder->andWhere("f.codigoClienteFk  = '{$codigoClienteFk}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("f.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("f.fecha <= '{$fechaHasta} 23:59:59'");
        }
        if ($numero) {
            $queryBuilder->andWhere("f.numero  = '{$numero}'");
        }
        if ($codigoFacturaTipoFk) {
            $queryBuilder->andWhere("f.codigoFacturaTipoFk  = '{$codigoFacturaTipoFk}'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAutorizado = 1");
                break;
        }

        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAprobado = 1");
                break;
        }

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAnulado = 1");
                break;
        }

        $queryBuilder->setMaxResults($limiteRegistros);
        $queryBuilder->addOrderBy('f.codigoFacturaPk', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function liquidar($arFactura)
    {
        /**
         * @var $arFactura TurFactura
         */
        $em = $this->getEntityManager();
        $respuesta = '';
//        $retencionFuente = $arFactura->getClienteRel()->getRetencionFuente();
//        $retencionFuenteSinBase = $arFactura->getClienteRel()->getRetencionFuenteSinBase();
        $retencionFuente = 1;
        $retencionFuenteSinBase = 0;
        $vrTotalBrutoGlobal = 0;
        $vrTotalGlobal = 0;
        $vrTotalNetoGlobal = 0;
        $vrDescuentoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrBaseAiuGlobal = 0;
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
            $vrBaseAiu = ($vrSubtotal * ($arFacturaDetalle->getPorcentajeBaseAiu()) / 100);
            $vrIva = $vrBaseAiu * ($arFacturaDetalle->getPorcentajeIva() / 100);
            $vrTotalBruto = $vrSubtotal;
            $vrTotal = $vrTotalBruto + $vrIva;
            $vrRetencionFuente = 0;
            $vrTotalGlobal += $vrTotal;
            $vrTotalBrutoGlobal += $vrTotalBruto;
            $vrDescuentoGlobal += $vrDescuento;
            $vrIvaGlobal += $vrIva;
            $vrBaseAiuGlobal += $vrBaseAiu;
            $vrSubtotalGlobal += $vrSubtotal;
            if ($arFacturaDetalle->getCodigoImpuestoRetencionFk()) {
                if ($retencionFuente) {
                    if ($arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['generaBase'] == true || $retencionFuenteSinBase) {
                        $vrRetencionFuente = ($vrSubtotal * $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['base'] / 100) * $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['porcentaje'] / 100;
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
        $arFactura->setVrBaseAiu($vrBaseAiuGlobal);
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
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['generaBase'] = true;
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['base'] = $arImpuesto->getBase();
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

    public function aprobar($arFactura)
    {
        $em = $this->getEntityManager();
        if ($arFactura->getEstadoAutorizado() == 1) {
            if ($arFactura->getEstadoAprobado() == 0) {
                $arFactura->setEstadoAprobado(1);
                $em->persist($arFactura);
                $em->flush();
            } else {
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

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFactura::class, 'f')
            ->select('f')
            ->leftJoin("f.clienteRel", "c");
        if ($session->get('filtroTurInformeComercialFacturaClienteCodigo') != null) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTurInformeComercialFacturaClienteCodigo')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaAutorizado') != null) {
            $queryBuilder->andWhere("f.estadoAutorizado = {$session->get('filtroTurInformeComercialFacturaAutorizado')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaAnulado') != null) {
            $queryBuilder->andWhere("f.estadoAnulado = {$session->get('filtroTurInformeComercialFacturaAnulado ')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaAprobado') != null) {
            $queryBuilder->andWhere("f.estadoAprobado = {$session->get('filtroTurInformeComercialFacturaAprobado')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaFechaDesde') != null) {
            $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroTurInformeComercialFacturaFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurInformeComercialFacturaFechaHasta') != null) {
            $queryBuilder->andWhere("f.fecha <= '{$session->get('filtroTurInformeComercialFacturaFechaHasta')} 23:59:59'");
        }
        if ($session->get('filtroTurInformeComercialFacturaCiudad') != null) {
            $queryBuilder->andWhere("c.codigoCiudadFk = {$session->get('filtroTurInformeComercialFacturaCiudad')}");
        }
        return $queryBuilder;
    }
}
