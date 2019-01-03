<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class FinConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arFinConfiguracion= $manager->getRepository('App:Financiero\FinConfiguracion')->find(1);
        if(!$arFinConfiguracion){
            $arFinConfiguracion = new \App\Entity\Financiero\FinConfiguracion();
            $arFinConfiguracion->setCodigoConfiguracionPk(1);
            $manager->persist($arFinConfiguracion);
        }
        $manager->flush();
    }
}
