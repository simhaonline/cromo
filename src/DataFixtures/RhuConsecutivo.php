<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuConsecutivo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConsecutivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuConsecutivo::class)->find(1);
        if(!$arConsecutivo){
            $arConsecutivo = new \App\Entity\RecursoHumano\RhuConsecutivo();
            $arConsecutivo->setCodigoConsecutivoPk(1);
            $arConsecutivo->setNombre("PAGO");
            $arConsecutivo->setConsecutivo(1);
            $manager->persist($arConsecutivo);
        }
        $arConsecutivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuConsecutivo::class)->find(2);
        if(!$arConsecutivo){
            $arConsecutivo = new \App\Entity\RecursoHumano\RhuConsecutivo();
            $arConsecutivo->setCodigoConsecutivoPk(2);
            $arConsecutivo->setNombre("PROVISION");
            $arConsecutivo->setConsecutivo(1);
            $manager->persist($arConsecutivo);
        }
        $arConsecutivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuConsecutivo::class)->find(3);
        if(!$arConsecutivo){
            $arConsecutivo = new \App\Entity\RecursoHumano\RhuConsecutivo();
            $arConsecutivo->setCodigoConsecutivoPk(3);
            $arConsecutivo->setNombre("LIQUIDACION");
            $arConsecutivo->setConsecutivo(1);
            $manager->persist($arConsecutivo);
        }
        $arConsecutivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuConsecutivo::class)->find(4);
        if(!$arConsecutivo){
            $arConsecutivo = new \App\Entity\RecursoHumano\RhuConsecutivo();
            $arConsecutivo->setCodigoConsecutivoPk(4);
            $arConsecutivo->setNombre("VACACION");
            $arConsecutivo->setConsecutivo(1);
            $manager->persist($arConsecutivo);
        }
        $manager->flush();
    }
}
