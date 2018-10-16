<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenEstadoCivil extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arEstadoCivil = $manager->getRepository(\App\Entity\General\GenEstadoCivil::class)->find('C');
        if(!$arEstadoCivil){
            $arEstadoCivil = new \App\Entity\General\GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('C');
            $arEstadoCivil->setNombre('CASADO');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(\App\Entity\General\GenEstadoCivil::class)->find('D');
        if(!$arEstadoCivil){
            $arEstadoCivil = new \App\Entity\General\GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('D');
            $arEstadoCivil->setNombre('DIVORSIADO');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(\App\Entity\General\GenEstadoCivil::class)->find('S');
        if(!$arEstadoCivil){
            $arEstadoCivil = new \App\Entity\General\GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('S');
            $arEstadoCivil->setNombre('SOLTERO');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(\App\Entity\General\GenEstadoCivil::class)->find('U');
        if(!$arEstadoCivil){
            $arEstadoCivil = new \App\Entity\General\GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('U');
            $arEstadoCivil->setNombre('UNIÓN LIBRE');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(\App\Entity\General\GenEstadoCivil::class)->find('V');
        if(!$arEstadoCivil){
            $arEstadoCivil = new \App\Entity\General\GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('V');
            $arEstadoCivil->setNombre('VIUDO');
            $manager->persist($arEstadoCivil);
        }

        $manager->flush();
    }
}
