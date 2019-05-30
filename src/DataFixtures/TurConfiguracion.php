<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class TurConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConfiguracion = $manager->getRepository(\App\Entity\Turno\TurConfiguracion::class)->find(1);
        if(!$arConfiguracion){
            $arConfiguracion = new \App\Entity\Turno\TurConfiguracion();
            $arConfiguracion->setCodigoConfiguracionPk(1);
            $manager->persist($arConfiguracion);
        }

        $manager->flush();
    }
}
