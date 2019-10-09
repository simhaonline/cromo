<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class TesMovimientoClase extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arMovimientoClase = $manager->getRepository(\App\Entity\Tesoreria\TesMovimientoClase::class)->find('EG');
        if(!$arMovimientoClase){
            $arMovimientoClase = new \App\Entity\Tesoreria\TesMovimientoClase();
            $arMovimientoClase->setCodigoMovimientoClasePk('EG');
            $arMovimientoClase->setNombre('EGRESO');
            $manager->persist($arMovimientoClase);
        }
        $manager->flush();
    }
}
