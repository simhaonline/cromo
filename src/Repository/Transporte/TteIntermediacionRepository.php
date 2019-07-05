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
use App\Entity\Transporte\TteIntermediacionCompra;
use App\Entity\Transporte\TteIntermediacionDetalle;
use App\Entity\Transporte\TteIntermediacionVenta;
use App\Entity\Transporte\TtePoseedor;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteIntermediacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteIntermediacion::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacion::class, 'i')
            ->select('i.codigoIntermediacionPk')
            ->addSelect('i.anio')
            ->addSelect('i.mes')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.vrFletePago')
            ->addSelect('i.vrFleteCobro')
            ->orderBy('i.anio', 'DESC');
        if ($session->get('filtroTteIntermediacionAnio') != '') {
            $queryBuilder->andWhere("i.anio LIKE '%{$session->get('filtroTteIntermediacionAnio')}%' ");
        }
        if ($session->get('filtroTteIntermediacioneMes') != '') {
            $queryBuilder->andWhere("i.mes = {$session->get('filtroTteIntermediacioneMes')} ");
        }
        return $queryBuilder;
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
            $fleteCobro = $em->getRepository(TteFactura::class)->fleteCobro($fechaDesde, $fechaHasta);
            $arIntermediacion->setVrFleteCobro($fleteCobro);
            $ingresoTotal = 0;
            $arrFleteCobroDetallados = $em->getRepository(TteFactura::class)->fleteCobroDetallado($fechaDesde, $fechaHasta);
            foreach ($arrFleteCobroDetallados as $arrFleteCobroDetallado) {
                $arCliente = $em->getRepository(TteCliente::class)->find($arrFleteCobroDetallado['codigoClienteFk']);
                $arFacturaTipo = $em->getRepository(TteFacturaTipo::class)->find($arrFleteCobroDetallado['codigoFacturaTipoFk']);
                $fleteCobroFactura = $arrFleteCobroDetallado['flete'];
                $participacion = ($fleteCobroFactura / $fleteCobro) * 100;
                $fleteParticipacion = $fletePago * $participacion / 100;
                $fleteIngreso = $fleteCobroFactura - $fleteParticipacion;
                $arIntermediacionVenta = new TteIntermediacionVenta();
                $arIntermediacionVenta->setClienteRel($arCliente);
                $arIntermediacionVenta->setFacturaTipoRel($arFacturaTipo);
                $arIntermediacionVenta->setIntermediacionRel($arIntermediacion);
                $arIntermediacionVenta->setAnio($arIntermediacion->getAnio());
                $arIntermediacionVenta->setMes($arIntermediacion->getMes());
                $arIntermediacionVenta->setPorcentajeParticipacion($participacion);
                $arIntermediacionVenta->setVrFlete($fleteCobroFactura);
                $arIntermediacionVenta->setVrFleteParticipacion($fleteParticipacion);
                $arIntermediacionVenta->setVrFleteIngreso($fleteIngreso);
                $em->persist($arIntermediacionVenta);
                $ingresoTotal += $fleteIngreso;
            }

            $arrFletePagoDetallados = $em->getRepository(TteDespacho::class)->fletePagoDetallado($fechaDesde, $fechaHasta);
            foreach ($arrFletePagoDetallados as $arrFletePagoDetallado) {
                $arPoseedor = $em->getRepository(TtePoseedor::class)->find($arrFletePagoDetallado['codigoPoseedorFk']);
                $fletePagoFactura = $arrFletePagoDetallado['fletePago'];
                $participacion = ($fletePagoFactura / $fletePago) * 100;
                $fleteParticipacion = $fletePago * $participacion / 100;
                $arIntermediacionCompra = new TteIntermediacionCompra();
                $arIntermediacionCompra->setPoseedorRel($arPoseedor);
                $arIntermediacionCompra->setIntermediacionRel($arIntermediacion);
                $arIntermediacionCompra->setAnio($arIntermediacion->getAnio());
                $arIntermediacionCompra->setMes($arIntermediacion->getMes());
                $arIntermediacionCompra->setPorcentajeParticipacion($participacion);
                $arIntermediacionCompra->setVrFlete($fletePagoFactura);
                $arIntermediacionCompra->setVrFleteParticipacion($fleteParticipacion);
                $em->persist($arIntermediacionCompra);
            }
            $arIntermediacion->setEstadoAutorizado(1);
            $arIntermediacion->setVrFleteCobro($fleteCobro);
            $arIntermediacion->setVrFletePago($fletePago);
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
            $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteIntermediacionVenta id WHERE id.codigoIntermediacionFk =' . $arIntermediacion->getCodigoIntermediacionPk());
            $numDeleted = $query->execute();
            $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteIntermediacionCompra id WHERE id.codigoIntermediacionFk =' . $arIntermediacion->getCodigoIntermediacionPk());
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