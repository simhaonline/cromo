<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class InvConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arInvConfiguracion = $manager->getRepository('App:Inventario\InvConfiguracion')->find(1);
        if(!$arInvConfiguracion){
            $arInvConfiguracion = new \App\Entity\Inventario\InvConfiguracion();
            $arInvConfiguracion->setCodigoConfiguracionPk(1);
            $arInvConfiguracion->setCodigoFormatoMovimiento(1);
            $arInvConfiguracion->setCodigoFormatoRemision(1);
            $manager->persist($arInvConfiguracion);
        }
        $manager->flush();
    }
}
