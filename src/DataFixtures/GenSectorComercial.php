<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenSectorComercial extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(1);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(1);
            $arSectorComercial->setNombre("COMERCIAL");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(2);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(2);
            $arSectorComercial->setNombre("CONSTRUCTOR");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(3);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(3);
            $arSectorComercial->setNombre("DIVERSION");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(4);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(4);
            $arSectorComercial->setNombre("EDUCATIVO");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(5);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(5);
            $arSectorComercial->setNombre("ESTATAL");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(6);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(6);
            $arSectorComercial->setNombre("FINANCIERO");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(7);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(7);
            $arSectorComercial->setNombre("GASTRONOMICO");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(8);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(8);
            $arSectorComercial->setNombre("HOTELERO");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(9);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(9);
            $arSectorComercial->setNombre("INDUSTRIAL");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(10);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(10);
            $arSectorComercial->setNombre("MINERO");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(11);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(11);
            $arSectorComercial->setNombre("RESIDENCIAL");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(12);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(12);
            $arSectorComercial->setNombre("SALUD");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(13);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(13);
            $arSectorComercial->setNombre("SERVICIOS");
            $manager->persist($arSectorComercial);
        }
        $arSectorComercial = $manager->getRepository(\App\Entity\General\GenSectorComercial::class)->find(14);
        if(!$arSectorComercial) {
            $arSectorComercial = new \App\Entity\General\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(14);
            $arSectorComercial->setNombre("TRANSPORTE TERRESTRE");
            $manager->persist($arSectorComercial);
        }
        $manager->flush();
    }
}