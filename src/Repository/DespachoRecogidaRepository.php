<?php

namespace App\Repository;

use App\Entity\DespachoRecogida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DespachoRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DespachoRecogida::class);
    }

    public function listaMovimiento(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dr.codigoDespachoRecogidaPk, 
        dr.fecha, 
        dr.codigoOperacionFk,
        dr.codigoRutaRecogidaFk,
        dr.cantidad,
        dr.unidades,
        dr.pesoReal,
        dr.pesoVolumen,
        dr.estadoDescargado
        FROM App\Entity\DespachoRecogida dr');
        return $query->execute();

    }

    public function liquidar($codigoDespachoRecogida): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades) as unidades, SUM(r.pesoReal) as pesoReal, SUM(r.pesoVolumen) as pesoVolumen
        FROM App\Entity\Recogida r
        WHERE r.codigoDespachoRecogidaFk = :codigoDespachoRecogida')
            ->setParameter('codigoDespachoRecogida', $codigoDespachoRecogida);
        $arrRecogidas = $query->execute();
        $arDespachoRecogida = $em->getRepository(DespachoRecogida::class)->find($codigoDespachoRecogida);
        $arDespachoRecogida->setUnidades($arrRecogidas['unidades']);
        $arDespachoRecogida->setPesoReal($arrRecogidas['pesoReal']);
        $arDespachoRecogida->setPesoVolumen($arrRecogidas['pesoVolumen']);
        $arDespachoRecogida->setCantidad($arrRecogidas['cantidad']);
        $em->persist($arDespachoRecogida);
        $em->flush();
        return true;
    }
}