<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteRecogida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDespachoRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoRecogida::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dr.codigoDespachoRecogidaPk, 
        dr.fecha, 
        dr.codigoOperacionFk,
        dr.codigoVehiculoFk,
        dr.codigoRutaRecogidaFk,
        dr.cantidad,
        dr.unidades,
        dr.pesoReal,
        dr.pesoVolumen,
        dr.estadoDescargado,
        dr.vrPago
        FROM App\Entity\Transporte\TteDespachoRecogida dr');
        return $query->execute();

    }

    public function liquidar($codigoDespachoRecogida): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades+0) as unidades, SUM(r.pesoReal+0) as pesoReal, SUM(r.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Transporte\TteRecogida r
        WHERE r.codigoDespachoRecogidaFk = :codigoDespachoRecogida')
            ->setParameter('codigoDespachoRecogida', $codigoDespachoRecogida);
        $arrRecogidas = $query->getSingleResult();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $arDespachoRecogida->setUnidades(intval($arrRecogidas['unidades']));
        $arDespachoRecogida->setPesoReal(intval($arrRecogidas['pesoReal']));
        $arDespachoRecogida->setPesoVolumen(intval($arrRecogidas['pesoVolumen']));
        $arDespachoRecogida->setCantidad(intval($arrRecogidas['cantidad']));
        $em->persist($arDespachoRecogida);
        $em->flush();
        return true;
    }

    public function retirarRecogida($arrRecogidas): bool
    {
        $em = $this->getEntityManager();
        if($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    if($arRecogida->getEstadoRecogido() == 0) {
                        $arRecogida->setDespachoRecogidaRel(null);
                        $arRecogida->setEstadoProgramado(0);
                        $em->persist($arRecogida);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    public function descargarRecogida($arrRecogidas): bool
    {
        $em = $this->getEntityManager();
        if($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    if($arRecogida->getEstadoRecogido() == 0 && $arRecogida->getUnidades() > 0 && $arRecogida->getPesoReal() > 0 && $arRecogida->getPesoVolumen() > 0) {
                        $arRecogida->setEstadoRecogido(1);
                        $em->persist($arRecogida);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }
}