<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionDetalle;
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
        i.estadoGenerado,
        i.vrFletePago,
        i.vrFletePagoRecogida,
        i.vrFletePagoTotal,
        i.vrFlete
        FROM App\Entity\Transporte\TteIntermediacion i                 
        ORDER BY i.anio, i.mes DESC '
        );
        return $query->execute();
    }

    public function generar($codigoIntermediacion): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($codigoIntermediacion);
        $ultimoDia = date("d", (mktime(0, 0, 0, $arIntermediacion->getMes() + 1, 1, $arIntermediacion->getAnio()) - 1));
        $fechaDesde = $arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-01 00:00:00";
        $fechaHasta = $arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-" . $ultimoDia . " 23:59:00";
        $fletePago = $em->getRepository(TteDespacho::class)->fletePago($fechaDesde, $fechaHasta);
        $fletePagoRecogida = $em->getRepository(TteDespachoRecogida::class)->fletePago($fechaDesde, $fechaHasta);
        $fletePagoTotal = $fletePago + $fletePagoRecogida;
        $fleteCobro = $em->getRepository(TteFactura::class)->fleteCobro($fechaDesde, $fechaHasta);
        $arIntermediacion->setVrFletePago($fletePago);
        $arIntermediacion->setVrFletePagoRecogida($fletePagoRecogida);
        $arIntermediacion->setVrFletePagoTotal($fletePagoTotal);
        $arIntermediacion->setVrFlete($fleteCobro);

        $ingresoTotal = 0;
        $arrFleteCobroDetallados = $em->getRepository(TteFactura::class)->fleteCobroDetallado($fechaDesde, $fechaHasta);
        foreach ($arrFleteCobroDetallados as $arrFleteCobroDetallado) {
            //$arFacturaTipo = $em->getRepository(TteFacturaTipo::class)->find($arrFleteCobroDetallado['codigoFacturaTipoFk']);
            $arCliente = $em->getRepository(TteCliente::class)->find($arrFleteCobroDetallado['codigoClienteFk']);
            $arIntermediacionDetalle = new TteIntermediacionDetalle();
            $arIntermediacionDetalle->setIntermediacionRel($arIntermediacion);
            $arIntermediacionDetalle->setAnio($arIntermediacion->getAnio());
            $arIntermediacionDetalle->setMes($arIntermediacion->getMes());
            $arIntermediacionDetalle->setClienteRel($arCliente);
            //$arIntermediacionDetalle->setFacturaTipoRel($arFacturaTipo);

            $flete = $arrFleteCobroDetallado['flete'];
            $participacion = 0;
            if($fleteCobro > 0) {
                $participacion = ($flete / $fleteCobro) * 100;
            }
            $pago = ($fletePagoTotal * $participacion) / 100;
            $ingreso = $flete - $pago;
            $arIntermediacionDetalle->setPorcentajeParticipacion($participacion);
            $arIntermediacionDetalle->setVrFlete($flete);
            $arIntermediacionDetalle->setVrPago($pago);
            $arIntermediacionDetalle->setVrIngreso($ingreso);
            $em->persist($arIntermediacionDetalle);
            $ingresoTotal += $ingreso;
        }
        $arIntermediacion->setEstadoGenerado(1);
        $arIntermediacion->setVrIngreso($ingresoTotal);
        $em->flush();
        return $respuesta;
    }
    public function deshacer($codigoIntermediacion): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($codigoIntermediacion);
        $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteIntermediacionDetalle id WHERE id.codigoIntermediacionFk =' . $codigoIntermediacion);
        $numDeleted = $query->execute();
        $arIntermediacion->setEstadoGenerado(0);
        $arIntermediacion->setVrFlete(0);
        $arIntermediacion->setVrFletePago(0);
        $arIntermediacion->setVrFletePagoRecogida(0);
        $arIntermediacion->setVrFletePagoTotal(0);
        $em->flush();
        return $respuesta;
    }


}