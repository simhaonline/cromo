<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class CarMovimientoClase extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arMovimientoClase = $manager->getRepository(\App\Entity\Cartera\CarMovimientoClase::class)->find('RC');
        if(!$arMovimientoClase){
            $arMovimientoClase = new \App\Entity\Cartera\CarMovimientoClase();
            $arMovimientoClase->setCodigoMovimientoClasePk('RC');
            $arMovimientoClase->setNombre('RECIBO');
            $manager->persist($arMovimientoClase);
        }
        $manager->flush();
    }
}
