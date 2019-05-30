<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class TurModalidad extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arModalidad = $manager->getRepository(\App\Entity\Turno\TurModalidad::class)->find('SAR');
        if(!$arModalidad){
            $arModalidad = new \App\Entity\Turno\TurModalidad();
            $arModalidad->setCodigoModalidadPk('SAR');
            $arModalidad->setNombre('SIN ARMA');
            $arModalidad->setPorcentaje(8);
            $manager->persist($arModalidad);
        }
        $arModalidad = $manager->getRepository(\App\Entity\Turno\TurModalidad::class)->find('CAR');
        if(!$arModalidad){
            $arModalidad = new \App\Entity\Turno\TurModalidad();
            $arModalidad->setCodigoModalidadPk('CAR');
            $arModalidad->setNombre('CON ARMA');
            $arModalidad->setPorcentaje(10);
            $manager->persist($arModalidad);
        }
        $arModalidad = $manager->getRepository(\App\Entity\Turno\TurModalidad::class)->find('CAN');
        if(!$arModalidad){
            $arModalidad = new \App\Entity\Turno\TurModalidad();
            $arModalidad->setCodigoModalidadPk('CAN');
            $arModalidad->setNombre('CANINO');
            $arModalidad->setPorcentaje(11);
            $manager->persist($arModalidad);
        }
        $manager->flush();
    }
}
