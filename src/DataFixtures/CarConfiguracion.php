<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CarConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConfiguracion = $manager->getRepository(\App\Entity\Cartera\CarConfiguracion::class)->find(1);
        if(!$arConfiguracion){
            $arConfiguracion = new \App\Entity\Cartera\CarConfiguracion();
            $arConfiguracion->setCodigoConfiguracionPk(1);
            $manager->persist($arConfiguracion);
        }
        $manager->flush();
    }
}
