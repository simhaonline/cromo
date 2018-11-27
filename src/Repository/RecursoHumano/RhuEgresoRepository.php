<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEgresoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEgreso::class);
    }

    /**
     * @param $codigoEgreso
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoEgreso){
        $em = $this->getEntityManager();
        $arEgreso = $em->getRepository(RhuEgreso::class)->find($codigoEgreso);
        $arEgresoDetalles = $em->getRepository(RhuEgresoDetalle::class)->findBy(['codigoEgresoFk' => $codigoEgreso]);
        $douTotal = 0;
        $intNumeroRegistros = 0;
        foreach ($arEgresoDetalles AS $arEgresoDetalle) {
            $douTotal += $arEgresoDetalle->getVrPago();
            $intNumeroRegistros++;
        }
        $arEgreso->setVrTotal($douTotal);
        $arEgreso->setNumeroRegistros($intNumeroRegistros);
        $em->persist($arEgreso);
        $em->flush();
    }
}