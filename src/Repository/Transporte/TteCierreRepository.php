<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteCierreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCierre::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.codigoCierrePk, 
        c.anio, 
        c.mes,
        c.estadoGenerado
        FROM App\Entity\Transporte\TteCierre c                 
        ORDER BY c.anio, c.mes DESC '
        );
        return $query->execute();
    }

    public function generar($codigoCierre): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arCierre = $em->getRepository(TteCierre::class)->find($codigoCierre);
        $arGuias = $em->getRepository(TteGuia::class)->periodoCierre($arCierre->getAnio(), $arCierre->getMes());
        foreach ($arGuias as $arGuia) {
            $arDespachoDetalles = $em->getRepository(TteDespachoDetalle::class)->guiaCosto($arGuia['codigoGuiaPk']);
            if($arDespachoDetalles) {
                $numeroDespachos = count($arDespachoDetalles);
                $precioPorDespacho = $arGuia['vrFlete'] / $numeroDespachos;
                $arGuiaObjeto = $em->getRepository(TteGuia::class)->find($arGuia['codigoGuiaPk']);
                foreach ($arDespachoDetalles as $arDespachoDetalle) {
                    $arCosto = new TteCosto();
                    $arCosto->setCierreRel($arCierre);
                    $arCosto->setGuiaRel($arGuiaObjeto);
                    $arCosto->setAnio($arCierre->getAnio());
                    $arCosto->setMes($arCierre->getMes());
                    $arCosto->setVrCostoUnidad($arDespachoDetalle['vrCostoUnidad']);
                    $arCosto->setVrCostoPeso($arDespachoDetalle['vrCostoPeso']);
                    $arCosto->setVrCostoVolumen($arDespachoDetalle['vrCostoVolumen']);
                    $arCosto->setVrCosto($arDespachoDetalle['vrCosto']);
                    $arCosto->setVrPrecio($precioPorDespacho);
                    $rentabilidad = $precioPorDespacho - $arDespachoDetalle['vrCosto'];
                    $arCosto->setVrRentabilidad($rentabilidad);
                    $em->persist($arCosto);
                }
            }
        }
        $arCierre->setEstadoGenerado(1);
        $em->flush();
        return $respuesta;
    }
    public function deshacer($codigoCierre): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arCierre = $em->getRepository(TteCierre::class)->find($codigoCierre);
        $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteCosto c WHERE c.codigoCierreFk =' . $codigoCierre);
        $numDeleted = $query->execute();
        $arCierre->setEstadoGenerado(0);
        $em->flush();
        return $respuesta;
    }


}