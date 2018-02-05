<?php

namespace App\Repository;

use App\Entity\Cumplido;
use App\Entity\Guia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CumplidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cumplido::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.codigoCumplidoPk, 
        c.fecha        
        FROM App\Entity\Cumplido c'
        );
        return $query->execute();
    }

    public function liquidar($codigoCumplido): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Guia g
        WHERE g.codigoCumplidoFk = :codigoCumplido')
            ->setParameter('codigoCumplido', $codigoCumplido);
        $arrGuias = $query->getSingleResult();
        $arCumplido = $em->getRepository(Cumplido::class)->find($codigoCumplido);
        $arCumplido->setCantidad(intval($arrGuias['cantidad']));
        $em->persist($arCumplido);
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
                    $arGuia->setCumplidoRel(null);
                    $arGuia->setEstadoCumplido(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

}