<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConfiguracion = $manager->getRepository(\App\Entity\RecursoHumano\RhuConfiguracion::class)->find(1);
        if(!$arConfiguracion){
            $arConfiguracion = new \App\Entity\RecursoHumano\RhuConfiguracion();
            $arConfiguracion->setCodigoConfiguracionPk(1);
            $arConfiguracion->setVrSalarioMinimo(0);
            $manager->persist($arConfiguracion);
        }
        $manager->flush();
    }
}
