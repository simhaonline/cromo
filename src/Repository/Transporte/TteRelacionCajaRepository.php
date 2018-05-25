<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRelacionCaja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteRelacionCajaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRelacionCaja::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT rc.codigoRelacionCajaPk, 
        rc.fecha,
        rc.vrTotal
        FROM App\Entity\Transporte\TteRelacionCaja rc         
        ORDER BY rc.codigoRelacionCajaPk DESC'
        );
        return $query->execute();

    }

    public function liquidar($codigoRelacionCaja): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoReciboPk) as cantidad, SUM(r.vrFlete+0) as flete, SUM(r.vrManejo+0) as manejo, SUM(r.vrTotal+0) as total
        FROM App\Entity\Transporte\TteRecibo r
        WHERE r.codigoRelacionCajaFk = :codigoRelacionCaja')
            ->setParameter('codigoRelacionCaja', $codigoRelacionCaja);
        $arr = $query->getSingleResult();
        $arMovimiento = $em->getRepository(TteRelacionCaja::class)->find($codigoRelacionCaja);
        $arMovimiento->setCantidad(intval($arr['cantidad']));
        $arMovimiento->setVrFlete(intval($arr['flete']));
        $arMovimiento->setVrManejo(intval($arr['manejo']));
        $arMovimiento->setVrTotal(intval($arr['total']));
        $em->persist($arMovimiento);
        $em->flush();
        return true;
    }

    public function retirarRecibo($arrDetalles): bool
    {
        $em = $this->getEntityManager();
        if($arrDetalles) {
            if (count($arrDetalles) > 0) {
                foreach ($arrDetalles AS $codigo) {
                    $arDetalle = $em->getRepository(TteRecibo::class)->find($codigo);
                    $arDetalle->setRelacionCajaRel(null);
                    $arDetalle->setEstadoRelacion(0);
                    $em->persist($arDetalle);
                }
                $em->flush();
            }
        }
        return true;
    }

}