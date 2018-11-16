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
            $arConsecutivo->setPago(1);
            $manager->persist($arConsecutivo);
        }
        $manager->flush();
    }
}
