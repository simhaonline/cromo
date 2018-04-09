<?php

namespace App\Repository\Transporte;


use App\Controller\Transporte\Buscar\ConductorController;
use App\Entity\Transporte\TteConsecutivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteConsecutivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteConsecutivo::class);
    }

    public function consecutivo($codigoConsecutivo) {
        $em = $this->getEntityManager();
        $intNumero = 0;
        $arConsecutivo = new TteConsecutivo();
        $arConsecutivo = $em->getRepository(TteConsecutivo::class)->find($codigoConsecutivo);
        $intNumero = $arConsecutivo->getConsecutivo();
        $arConsecutivo->setConsecutivo($intNumero + 1);
        $em->persist($arConsecutivo);
        return $intNumero;
    }


}