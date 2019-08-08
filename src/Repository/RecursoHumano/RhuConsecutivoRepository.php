<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuConsecutivo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuConsecutivoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConsecutivo::class);
    }

    public function consecutivo($codigoConsecutivo) {
        $em = $this->getEntityManager();
        $intNumero = 0;
        $arConsecutivo = $em->getRepository(RhuConsecutivo::class)->find($codigoConsecutivo);
        $intNumero = $arConsecutivo->getConsecutivo();
        $arConsecutivo->setConsecutivo($intNumero + 1);
        $em->persist($arConsecutivo);
        return $intNumero;
    }

}