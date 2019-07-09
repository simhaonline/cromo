<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuConfiguracionAporte extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConfiguracion = $manager->getRepository(\App\Entity\RecursoHumano\RhuConfiguracionAporte::class)->find(1);
        if(!$arConfiguracion){
            $arConfiguracion = new \App\Entity\RecursoHumano\RhuConfiguracionAporte();
            $arConfiguracion->setCodigoConfiguracionAportePk(1);
            $manager->persist($arConfiguracion);
        }
        $manager->flush();
    }
}
