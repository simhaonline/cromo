<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenSectorEconomico extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(1);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(1);
            $arGenSectorEconomico->setNombre("COMERCIO");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(2);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(2);
            $arGenSectorEconomico->setNombre("CONSTRUCCION");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(3);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(3);
            $arGenSectorEconomico->setNombre("EDUCATIVO");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(4);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(4);
            $arGenSectorEconomico->setNombre("INDUSTRIAL");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(5);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(5);
            $arGenSectorEconomico->setNombre("MINERO Y ENERGETICO");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(6);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(6);
            $arGenSectorEconomico->setNombre("PROPIEDAD HORIZONTAL");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(7);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(7);
            $arGenSectorEconomico->setNombre("RESIDENCIAL");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(8);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(8);
            $arGenSectorEconomico->setNombre("SALUD");
            $manager->persist($arGenSectorEconomico);
        }

        $arGenSectorEconomico = $manager->getRepository(\App\Entity\General\GenSectorEconomico::class)->find(9);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \App\Entity\General\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(9);
            $arGenSectorEconomico->setNombre("SERVICIOS");
            $manager->persist($arGenSectorEconomico);
        }
        $manager->flush();
    }
}