<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteFacturaDetalle;
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
            $arGuiaObjeto = $em->getRepository(TteGuia::class)->find($arGuia['codigoGuiaPk']);
            $costo = 0;
            $costoPeso = 0;
            $costoVolumen = 0;
            $costoUnidad = 0;
            $arrCostos = $em->getRepository(TteDespachoDetalle::class)->guiaCosto($arGuia['codigoGuiaPk']);
            if($arrCostos && $arrCostos != null) {
                $costo = $arrCostos['vrCosto']+0;
                $costoPeso = $arrCostos['vrCostoPeso']+0;
                $costoVolumen = $arrCostos['vrCostoVolumen']+0;
                $costoUnidad = $arrCostos['vrCostoUnidad']+0;
            }
            $precio = 0;
            $arrPrecios = $em->getRepository(TteFacturaDetalle::class)->guiaPrecio($arGuia['codigoGuiaPk']);
            if($arrPrecios && $arrPrecios != null) {
                $precio = $arrPrecios['vrFlete']+0;
            }
            $arCosto = new TteCosto();
            $arCosto->setCierreRel($arCierre);
            $arCosto->setGuiaRel($arGuiaObjeto);
            $arCosto->setCiudadDestinoRel($arGuiaObjeto->getCiudadDestinoRel());
            $arCosto->setClienteRel($arGuiaObjeto->getClienteRel());
            $arCosto->setAnio($arCierre->getAnio());
            $arCosto->setMes($arCierre->getMes());
            $arCosto->setVrCosto($costo);
            $arCosto->setVrCostoPeso($costoPeso);
            $arCosto->setVrCostoVolumen($costoVolumen);
            $arCosto->setVrCostoUnidad($costoUnidad);
            $arCosto->setVrPrecio($precio);
            $rentabilidad = $precio - $costo;
            $arCosto->setVrRentabilidad($rentabilidad);
            $porcentajeRentabilidad = 0;
            if ($precio > 0) {
                $porcentajeRentabilidad = ($rentabilidad / $precio) * 100;
            }

            $arCosto->setPorcentajeRentabilidad($porcentajeRentabilidad);
            $em->persist($arCosto);

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