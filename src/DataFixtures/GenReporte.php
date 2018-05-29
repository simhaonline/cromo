<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenReporte extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arReporte = $manager->getRepository(\App\Entity\General\GenReporte::class)->find(1);
        if(!$arReporte) {
            $arReporte = new \App\Entity\General\GenReporte();
            $arReporte->setCodigoReportePk("1");
            $arReporte->setModulo('Transporte');
            $arReporte->setNombre('Recibo caja');
            $arReporte->setArchivo('FormatoRecibo.rpt');
            $manager->persist($arReporte);
        }
        $arReporte = $manager->getRepository(\App\Entity\General\GenReporte::class)->find(2);
        if(!$arReporte) {
            $arReporte = new \App\Entity\General\GenReporte();
            $arReporte->setCodigoReportePk("2");
            $arReporte->setModulo('Transporte');
            $arReporte->setNombre('Guia');
            $arReporte->setArchivo('FormatoGuia.rpt');
            $manager->persist($arReporte);
        }
        $manager->flush();
    }
}
