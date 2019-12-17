<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenCobertura extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arCobertura = $manager->getRepository(\App\Entity\General\GenCobertura::class)->find(1);
        if(!$arCobertura) {
            $arCobertura = new \App\Entity\General\GenCobertura();
            $arCobertura->setCodigoCoberturaPk(1);
            $arCobertura->setNombre("LOCAL");
            $manager->persist($arCobertura);
        }
        $arCobertura = $manager->getRepository(\App\Entity\General\GenCobertura::class)->find(2);
        if(!$arCobertura) {
            $arCobertura = new \App\Entity\General\GenCobertura();
            $arCobertura->setCodigoCoberturaPk(2);
            $arCobertura->setNombre("INTERNACIONAL");
            $manager->persist($arCobertura);
        }
        $arCobertura = $manager->getRepository(\App\Entity\General\GenCobertura::class)->find(3);
        if(!$arCobertura) {
            $arCobertura = new \App\Entity\General\GenCobertura();
            $arCobertura->setCodigoCoberturaPk(3);
            $arCobertura->setNombre("NACIONAL");
            $manager->persist($arCobertura);
        }
        $manager->flush();
    }
}