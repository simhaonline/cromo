<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvCotizacion;
use App\Entity\Inventario\InvCotizacionDetalle;
use App\Entity\Inventario\InvCotizacionTipo;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class InvCotizacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvCotizacion::class);
    }

    /**
     * @return mixed
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvCotizacion::class, 'i')
            ->select('i.codigoCotizacionPk')
            ->join('i.cotizacionTipoRel', 'it')
            ->join('i.terceroRel', 't')
            ->addSelect('i.numero')
            ->addSelect('t.nombreCorto as cliente')
            ->addSelect('it.nombre as nombreTipo')
            ->addSelect('i.fecha')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->where('i.codigoCotizacionPk <> 0');
        if ($session->get('filtroInvSolicitudNumero') != '') {
            $queryBuilder->andWhere("i.numero = {$session->get('filtroInvCotizacionNumero')}");
        }
        switch ($session->get('filtroInvCotizacionEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("i.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAprobado = 1");
                break;
        }
        if ($session->get('filtroInvCotizacionTipoCodigo')) {
            $queryBuilder->andWhere("i.codigoCotizacionTipoFk = '{$session->get('filtroInvCotizacionTipoCodigo')}'");
        }
        return $queryBuilder;
    }

    /**
     * @param $arCotizacion InvCotizacion
     * @throws \Doctrine\ORM\ORMException
     */
    public function aprobar($arCotizacion)
    {
        $arCotizacionTipo = $this->_em->getRepository(InvCotizacionTipo::class)->find($arCotizacion->getCodigoCotizacionTipoFk());
        if (!$arCotizacion->getEstadoAprobado()) {
            if ($arCotizacion->getNumero() == 0 || $arCotizacion->getNumero() == "") {
                $arCotizacionTipo->setConsecutivo($arCotizacionTipo->getConsecutivo() + 1);
                $arCotizacion->setNumero($arCotizacionTipo->getConsecutivo());
                $this->_em->persist($arCotizacionTipo);
            }
            $arCotizacion->setEstadoAprobado(1);
            $this->_em->persist($arCotizacion);
            $this->_em->flush();
        }
    }

    /**
     * @param $arCotizacion InvCotizacion
     * @return array
     */
    public function anular($arCotizacion)
    {
        $respuesta = [];
        if ($arCotizacion->getEstadoAprobado() == 1) {
            $arCotizacion->setEstadoAnulado(1);
            $this->_em->persist($arCotizacion);
            if (count($respuesta) == 0) {
                $this->_em->flush();
            }
            return $respuesta;
        }
    }

    /**
     * @param $arCotizacion InvCotizacion
     */
    public function desautorizar($arCotizacion)
    {
        if ($arCotizacion->getEstadoAutorizado() == 1 && $arCotizacion->getEstadoAprobado() == 0) {
            $arCotizacion->setEstadoAutorizado(0);
            $this->_em->persist($arCotizacion);
            $this->_em->flush();
        } else {
            Mensajes::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arCotizacion InvCotizacion
     */
    public function autorizar($arCotizacion)
    {
        if (count($this->_em->getRepository(InvCotizacionDetalle::class)->findBy(['codigoCotizacionFk' => $arCotizacion->getCodigoCotizacionPk()])) > 0) {
            $arCotizacion->setEstadoAutorizado(1);
            $this->_em->persist($arCotizacion);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arSolicitud InvSolicitud
         */
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvCotizacion::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvCotizacionDetalle::class)->findBy(['codigoCotizacionFk' => $arRegistro->getCodigoCotizacionPk()])) <= 0) {
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

    /**
     * @param $arCotizacion InvCotizacion
     * @param $arrValor
     * @param $arrCantidad
     * @param $arrIva
     * @param $arrDescuento
     */
    public function actualizar($arCotizacion, $arrValor, $arrCantidad, $arrIva, $arrDescuento)
    {
        $vrTotalGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $vrDctoGlobal = 0;
        $arCotizacionDetalles = $this->_em->getRepository(InvCotizacionDetalle::class)->findBy(['codigoCotizacionFk' => $arCotizacion->getCodigoCotizacionPk()]);
        if (count($arCotizacionDetalles) > 0) {
            foreach ($arCotizacionDetalles as $arCotizacionDetalle) {
                $id = $arCotizacionDetalle->getCodigoCotizacionDetallePk();
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

                $arCotizacionDetalle->setPorcentajeIva($porIva);
                $arCotizacionDetalle->setPorcentajeDescuento($porDcto);
                $arCotizacionDetalle->setVrDescuento($vrDcto);
                $arCotizacionDetalle->setCantidad($cantidad);
                $arCotizacionDetalle->setVrPrecio($vrUnitario);
                $arCotizacionDetalle->setVrSubtotal($vrSubtotal);
                $arCotizacionDetalle->setVrIva($vrIva);
                $arCotizacionDetalle->setVrTotal($vrTotal);
                $this->_em->persist($arCotizacionDetalle);
            }

            $arCotizacion->setVrDescuento($vrDctoGlobal);
            $arCotizacion->setVrIva($vrIvaGlobal);
            $arCotizacion->setVrSubtotal($vrSubtotalGlobal);
            $arCotizacion->setVrTotal($vrTotalGlobal);
            $this->_em->persist($arCotizacion);
        } else {
            $arCotizacion->setVrDescuento(0);
            $arCotizacion->setVrIva(0);
            $arCotizacion->setVrSubtotal(0);
            $arCotizacion->setVrTotal(0);
            $this->_em->persist($arCotizacion);
        }
        $this->_em->flush();
    }

    private function validarDetalleEnuso($codigoOrdenCompraDetalle)
    {
        $respuesta = [];
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvMovimientoDetalle', 'imd')
            ->select('imd.codigoMovimientoDetallePk')
            ->addSelect('imd.codigoMovimientoFk')
            ->join('imd.movimientoRel', 'm')
            ->where("imd.codigoOrdenCompraDetalleFk = {$codigoOrdenCompraDetalle}")
            ->andWhere('m.estadoAnulado = 0');
        $query = $this->_em->createQuery($qb->getDQL());
        $resultado = $query->execute();
        if (count($resultado) > 0) {
            foreach ($resultado as $result) {
                $respuesta[] = 'No se puede anular, el detalle con ID ' . $codigoOrdenCompraDetalle . ' esta siendo utilizado en el movimiento con ID ' . $result['codigoMovimientoFk'];
            }
        }
        return $respuesta;
    }
}