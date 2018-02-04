<?php

namespace App\Repository;

use App\Entity\Despacho;
use App\Entity\Guia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DespachoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Despacho::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT d.codigoDespachoPk, 
        d.numero,
        d.codigoOperacionFk,
        d.codigoVehiculoFk,
        d.codigoRutaFk, 
        co.nombre AS ciudadOrigen, 
        cd.nombre AS ciudadDestino,
        d.unidades,
        d.pesoReal,
        d.pesoVolumen,
        d.vrFlete,
        d.vrManejo,
        d.vrDeclara
        FROM App\Entity\Despacho d         
        LEFT JOIN d.ciudadOrigenRel co
        LEFT JOIN d.ciudadDestinoRel cd'
        );
        return $query->execute();

    }

    public function liquidar($codigoDespacho): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Guia g
        WHERE g.codigoDespachoFk = :codigoDespacho')
            ->setParameter('codigoDespacho', $codigoDespacho);
        $arrGuias = $query->getSingleResult();
        $arDespacho = $em->getRepository(Despacho::class)->find($codigoDespacho);
        $arDespacho->setUnidades(intval($arrGuias['unidades']));
        $arDespacho->setPesoReal(intval($arrGuias['pesoReal']));
        $arDespacho->setPesoVolumen(intval($arrGuias['pesoVolumen']));
        $arDespacho->setCantidad(intval($arrGuias['cantidad']));
        $em->persist($arDespacho);
        $em->flush();
        return true;
    }

    public function imprimirManifiesto($codigoDespacho): bool
    {
        $em = $this->getEntityManager();
        $arDespacho = $em->getRepository(Despacho::class)->find($codigoDespacho);
        if(!$arDespacho->getEstadoGenerado()) {
            $fechaActual = new \DateTime('now');
            $query = $em->createQuery('UPDATE App\Entity\Guia g set g.estadoDespachado = 1, g.fechaDespacho=:fecha 
                      WHERE g.codigoDespachoFk = :codigoDespacho')
                ->setParameter('codigoDespacho', $codigoDespacho)
                ->setParameter('fecha', $fechaActual->format('Y-m-d H:i'));
            $query->execute();
            $arDespacho->setFechaSalida($fechaActual);
            $arDespacho->setEstadoGenerado(1);
            $em->persist($arDespacho);
            $em->flush();
        }

        return true;
    }

    public function retirarGuia($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(Guia::class)->find($codigoGuia);
                    $arGuia->setDespachoRel(null);
                    $arGuia->setEstadoEmbarcado(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }
}