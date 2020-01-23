<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuConfiguracionLiquidacion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConfiguracion = $manager->getRepository(\App\Entity\RecursoHumano\RhuConfiguracionLiquidacion::class)->find(1);
        if(!$arConfiguracion){
            $arConfiguracion = new \App\Entity\RecursoHumano\RhuConfiguracionLiquidacion();
            $arConfiguracion->setCodigoConfiguracionLiquidacionPk(1);
            $manager->persist($arConfiguracion);
        }
        $manager->flush();
    }
}
