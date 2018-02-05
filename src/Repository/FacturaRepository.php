<?php

namespace App\Repository;

use App\Entity\Factura;
use App\Entity\Guia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Factura::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.codigoFacturaPk, 
        c.fecha        
        FROM App\Entity\Factura c'
        );
        return $query->execute();
    }

    public function liquidar($codigoFactura): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Guia g
        WHERE g.codigoFacturaFk = :codigoFactura')
            ->setParameter('codigoFactura', $codigoFactura);
        $arrGuias = $query->getSingleResult();
        $arFactura = $em->getRepository(Factura::class)->find($codigoFactura);
        $arFactura->setCantidad(intval($arrGuias['cantidad']));
        $em->persist($arFactura);
        $em->flush();
        return true;
    }

    public function retirarGuia($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(Guia::class)->find($codigoGuia);
                    $arGuia->setFacturaRel(null);
                    $arGuia->setEstadoFactura(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

}