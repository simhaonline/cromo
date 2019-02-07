<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvCosto;
use App\Entity\Inventario\InvMovimientoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Utilidades\Mensajes;

class InvCostoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvCosto::class);
    }

    /**
     * @param $codigoCosto
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoCosto)
    {
        $em = $this->getEntityManager();
        $arCosto = $em->getRepository(InvCosto::class)->find($codigoCosto);
        $vrTotalCosto = $em->getRepository(InvCostoCosto::class)->totalCostos($arCosto->getCodigoCostoPk());
        $subtotalGeneralExtranjero = 0;
        $subtotalGeneralExtranjeroTemporal = 0;
        $ivaGeneralExtranjero = 0;
        $totalGeneralExtranjero = 0;
        $subtotalGeneralLocal = 0;
        $subtotalGeneralLocalBruto = 0;
        $ivaGeneralLocal = 0;
        $totalGeneralLocal = 0;
        $arCostoDetalles = $em->getRepository(InvCostoDetalle::class)->findBy(['codigoCostoFk' => $codigoCosto]);
        foreach ($arCostoDetalles as $arCostoDetalle) {
            $subtotalGeneralExtranjeroTemporal += $arCostoDetalle->getCantidad() * $arCostoDetalle->getVrPrecioExtranjero();
        }
        foreach ($arCostoDetalles as $arCostoDetalle) {
            $subtotalExtranjero = $arCostoDetalle->getCantidad() * $arCostoDetalle->getVrPrecioExtranjero();
            $porcentajeIvaExtranjero = $arCostoDetalle->getPorcentajeIvaExtranjero();
            $ivaExtranjero = $subtotalExtranjero * $porcentajeIvaExtranjero / 100;
            $subtotalGeneralExtranjero += $subtotalExtranjero;
            $ivaGeneralExtranjero += $ivaExtranjero;
            $totalExtranjero = $subtotalExtranjero + $ivaExtranjero;
            $totalGeneralExtranjero += $totalExtranjero;
            $arCostoDetalle->setVrSubtotalExtranjero($subtotalExtranjero);
            $arCostoDetalle->setVrIvaExtranjero($ivaExtranjero);
            $arCostoDetalle->setVrTotalExtranjero($totalExtranjero);

            $precioLocal = $arCostoDetalle->getVrPrecioExtranjero() * $arCosto->getTasaRepresentativaMercado();
            $porcentajeParticipaCosto = 0;
            $costoParticipa = 0;
            if ($vrTotalCosto > 0) {
                if ($subtotalGeneralExtranjeroTemporal > 0) {
                    $porcentajeParticipaCosto = ($arCostoDetalle->getVrSubtotalExtranjero() / $subtotalGeneralExtranjeroTemporal) * 100;
                    $costoParticipa = (($vrTotalCosto * $porcentajeParticipaCosto) / 100) / $arCostoDetalle->getCantidad();
                }

            }
            $precioLocalTotal = $arCostoDetalle->getVrPrecioLocal() + $costoParticipa;
            $subtotalLocalBruto = $arCostoDetalle->getCantidad() * $precioLocal;
            $subtotalLocal = $arCostoDetalle->getCantidad() * $precioLocalTotal;
            $porcentajeIvaLocal = $arCostoDetalle->getPorcentajeIvaLocal();
            $ivaLocal = $subtotalExtranjero * $porcentajeIvaLocal / 100;
            $subtotalGeneralLocal += $subtotalLocal;
            $subtotalGeneralLocalBruto += $subtotalLocalBruto;
            $ivaGeneralLocal += $ivaLocal;
            $totalLocal = $subtotalLocal + $ivaLocal;
            $totalGeneralLocal += $totalLocal;
            $arCostoDetalle->setVrPrecioLocal($precioLocal);
            $arCostoDetalle->setVrPrecioLocalTotal($precioLocalTotal);
            $arCostoDetalle->setVrSubtotalLocal($subtotalLocal);
            $arCostoDetalle->setVrSubtotalLocalBruto($subtotalLocalBruto);
            $arCostoDetalle->setVrIvaLocal($ivaLocal);
            $arCostoDetalle->setVrTotalLocal($totalLocal);
            $arCostoDetalle->setPorcentajeParticipaCosto($porcentajeParticipaCosto);
            $arCostoDetalle->setVrCostoParticipa($costoParticipa);
            $em->persist($arCostoDetalle);
        }

        $arCosto->setVrTotalCosto($vrTotalCosto);
        $arCosto->setVrSubtotalExtranjero($subtotalGeneralExtranjero);
        $arCosto->setVrIvaExtranjero($ivaGeneralExtranjero);
        $arCosto->setVrTotalExtranjero($totalGeneralExtranjero);

        $arCosto->setVrSubtotalLocal($subtotalGeneralLocal);
        $arCosto->setVrSubtotalLocalBruto($subtotalGeneralLocalBruto);
        $arCosto->setVrIvaLocal($ivaGeneralLocal);
        $arCosto->setVrTotalLocal($totalGeneralLocal);

        $em->persist($arCosto);
        $em->flush();
    }

    /**
     * @param $arCosto InvCosto
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arCosto)
    {
        $em = $this->getEntityManager();
        if (!$arCosto->getEstadoAutorizado()) {
            $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->costoVentas($arCosto->getAnio(), $arCosto->getMes());

            $arCosto->setEstadoAutorizado(1);
            $em->persist($arCosto);
            $em->flush();
        }
    }

    /**
     * @param $arCosto InvCosto
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCosto)
    {
        $em = $this->getEntityManager();
        if ($arCosto->getEstadoAutorizado()) {
            $arCosto->setEstadoAutorizado(0);
            $em->persist($arCosto);
            $em->flush();
        }
    }    
    
}