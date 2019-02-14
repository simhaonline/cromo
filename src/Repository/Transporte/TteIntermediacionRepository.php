<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteConsecutivo;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteIntermediacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteIntermediacion::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT i.codigoIntermediacionPk, 
        i.anio, 
        i.mes,
        i.estadoAutorizado,
        i.estadoAprobado, 
        i.vrFletePago,
        i.vrFleteCobro
        FROM App\Entity\Transporte\TteIntermediacion i                 
        ORDER BY i.anio, i.mes DESC '
        );
        return $query->execute();
    }

    /**
     * @param $arIntermediacion TteIntermediacion
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arIntermediacion)
    {
        $em = $this->getEntityManager();
        if(!$arIntermediacion->getEstadoAutorizado()) {
            $ultimoDia = date("d", (mktime(0, 0, 0, $arIntermediacion->getMes() + 1, 1, $arIntermediacion->getAnio()) - 1));
            $fechaDesde = $arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-01 00:00:00";
            $fechaHasta = $arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-" . $ultimoDia . " 23:59:00";
            $fletePago = $em->getRepository(TteDespacho::class)->fletePago($fechaDesde, $fechaHasta);
            //$fletePagoRecogida = $em->getRepository(TteDespachoRecogida::class)->fletePago($fechaDesde, $fechaHasta);
            //$fletePagoTotal = $fletePago + $fletePagoRecogida;
            $fleteCobro = $em->getRepository(TteFactura::class)->fleteCobro($fechaDesde, $fechaHasta);
            $arIntermediacion->setVrFletePago($fletePago);
            $arIntermediacion->setVrFleteCobro($fleteCobro);
            $prueba = 0;
            $ingresoTotal = 0;
            $arrFleteCobroDetallados = $em->getRepository(TteFactura::class)->fleteCobroDetallado($fechaDesde, $fechaHasta);
            foreach ($arrFleteCobroDetallados as $arrFleteCobroDetallado) {
                $fleteCobroFactura = $arrFleteCobroDetallado['flete'];
                $prueba += $fleteCobroFactura;
                $participacion = ($fleteCobroFactura / $fleteCobro) * 100;
                $fletePagoFactura = $fletePago * $participacion / 100;
                $arIntermediacionDetalle = new TteIntermediacionDetalle();
                $arIntermediacionDetalle->setIntermediacionRel($arIntermediacion);
                $arIntermediacionDetalle->setAnio($arIntermediacion->getAnio());
                $arIntermediacionDetalle->setMes($arIntermediacion->getMes());
                $arIntermediacionDetalle->setFecha($arIntermediacion->getFecha());
                $arIntermediacionDetalle->setPorcentajeParticipacion($participacion);
                $arIntermediacionDetalle->setVrFlete($fleteCobroFactura);
                $arIntermediacionDetalle->setVrPago($fletePagoFactura);
                $em->persist($arIntermediacionDetalle);
            }


            /*$arrFletePagoDetallados = $em->getRepository(TteDespacho::class)->fletePagoDetallado($fechaDesde, $fechaHasta);
            $arrFleteCobroDetallados = $em->getRepository(TteFactura::class)->fleteCobroDetallado($fechaDesde, $fechaHasta);
            foreach ($arrFletePagoDetallados as $arrFletePagoDetallado) {
                $arDespachoTipo = $em->getRepository(TteDespachoTipo::class)->find($arrFletePagoDetallado['codigoDespachoTipoFk']);
                foreach ($arrFleteCobroDetallados as $arrFleteCobroDetallado) {
                    $arFacturaTipo = $em->getRepository(TteFacturaTipo::class)->find($arrFleteCobroDetallado['codigoFacturaTipoFk']);
                    $arCliente = $em->getRepository(TteCliente::class)->find($arrFleteCobroDetallado['codigoClienteFk']);
                    $arIntermediacionDetalle = new TteIntermediacionDetalle();
                    $arIntermediacionDetalle->setIntermediacionRel($arIntermediacion);
                    $arIntermediacionDetalle->setAnio($arIntermediacion->getAnio());
                    $arIntermediacionDetalle->setMes($arIntermediacion->getMes());
                    $arIntermediacionDetalle->setFecha($arIntermediacion->getFecha());
                    $arIntermediacionDetalle->setClienteRel($arCliente);
                    $arIntermediacionDetalle->setFacturaTipoRel($arFacturaTipo);
                    $arIntermediacionDetalle->setDespachoTipoRel($arDespachoTipo);

                    $flete = $arrFleteCobroDetallado['flete'];
                    $participacion = ($flete / $fleteCobro) * 100;

                    $fletePagoTipo = $arrFletePagoDetallado['fletePago'];
                    $pago = ($fletePagoTipo * $participacion) / 100;
                    $ingreso = $flete - $pago;
                    $arIntermediacionDetalle->setPorcentajeParticipacion($participacion);
                    $arIntermediacionDetalle->setVrFlete($flete);
                    $arIntermediacionDetalle->setVrPago($pago);
                    $arIntermediacionDetalle->setVrIngreso($ingreso);
                    $arIntermediacionDetalle->setVrPagoOperado($pago * $arFacturaTipo->getOperacionComercial());
                    $arIntermediacionDetalle->setVrIngresoOperado($ingreso * $arFacturaTipo->getOperacionComercial());
                    $em->persist($arIntermediacionDetalle);
                    $ingresoTotal += $ingreso * $arFacturaTipo->getOperacionComercial();
                }
            }
            */
            $arIntermediacion->setEstadoAutorizado(1);
            $arIntermediacion->setVrIngreso($ingresoTotal);
            $em->flush();
        } else {
            Mensajes::error("El documento ya esta autorizado");
        }
    }

    /**
     * @param $arIntermediacion TteIntermediacion
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desAutorizar($arIntermediacion)
    {
        $em = $this->getEntityManager();
        if($arIntermediacion->getEstadoAutorizado() && !$arIntermediacion->getEstadoAprobado()) {
            $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteIntermediacionDetalle id WHERE id.codigoIntermediacionFk =' . $arIntermediacion->getCodigoIntermediacionPk());
            $numDeleted = $query->execute();
            $arIntermediacion->setEstadoAutorizado(0);
            $arIntermediacion->setVrFleteCobro(0);
            $arIntermediacion->setVrFletePago(0);
            $arIntermediacion->setVrIngreso(0);
            $em->persist($arIntermediacion);
            $em->flush();
        } else {
            Mensajes::error("El documento no esta autorizado o esta aprobado");
        }
    }

    /**
     * @param $arIntermediacion TteIntermediacion
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arIntermediacion)
    {
        $em = $this->getEntityManager();
        if($arIntermediacion->getEstadoAutorizado() && !$arIntermediacion->getEstadoAprobado()) {
            $arConsecutivo = $em->getRepository(TteConsecutivo::class)->find(1);
            $arIntermediacionDetalles = $em->getRepository(TteIntermediacionDetalle::class)->findBy(array('codigoIntermediacionFk' => $arIntermediacion->getCodigoIntermediacionPk()));
            foreach ($arIntermediacionDetalles as $arIntermediacionDetalle) {
                $arIntermediacionDetalle->setNumero($arConsecutivo->getIntermediacion());
                $em->persist($arIntermediacionDetalle);
                $arConsecutivo->setIntermediacion($arConsecutivo->getIntermediacion() + 1);
            }
            $em->persist($arConsecutivo);
            $arIntermediacion->setEstadoAprobado(1);
            $em->persist($arIntermediacion);
            $em->flush();
        } else {
            Mensajes::error("El documento no esta autorizado o esta aprobado");
        }
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteIntermediacion::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if (count($this->getEntityManager()->getRepository(TteIntermediacionDetalle::class)->findBy(['codigoIntermediacionFk' => $arRegistro->getCodigoIntermediacionPk()])) <= 0) {
                            $this->getEntityManager()->remove($arRegistro);
                        } else {
                            $respuesta = 'No se puede eliminar, el registro tiene detalles';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado o autorizado';
                    }
                }
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

}