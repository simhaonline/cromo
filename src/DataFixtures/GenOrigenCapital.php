<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenOrigenCapital extends Fixture
{

    public function load(ObjectManager $manager) {

        $arOrigenCapital = $manager->getRepository(\App\Entity\General\GenOrigenCapital::class)->find(1);
        if (!$arOrigenCapital) {
            $arOrigenCapital = new \App\Entity\General\GenOrigenCapital();
            $arOrigenCapital->setCodigoOrigenCapitalPk(1);
            $arOrigenCapital->setNombre("ESTATAL");
            $manager->persist($arOrigenCapital);
        }
        $arOrigenCapital = $manager->getRepository(\App\Entity\General\GenOrigenCapital::class)->find(2);
        if (!$arOrigenCapital) {
            $arOrigenCapital = new \App\Entity\General\GenOrigenCapital();
            $arOrigenCapital->setCodigoOrigenCapitalPk(2);
            $arOrigenCapital->setNombre("PRIVADO");
            $manager->persist($arOrigenCapital);
        }
        $manager->flush();
    }

}