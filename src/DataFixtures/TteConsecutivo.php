<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TteConsecutivo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConsecutivo= $manager->getRepository('App:Transporte\TteConsecutivo')->find(1);
        if(!$arConsecutivo){
            $arConsecutivo = new \App\Entity\Transporte\TteConsecutivo();
            $arConsecutivo->setCodigoConsecutivoPk(1);
            $arConsecutivo->setIntermediacion(1);
            $manager->persist($arConsecutivo);
        }
        $manager->flush();
    }
}