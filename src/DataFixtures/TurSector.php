<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class TurSector extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arSector = $manager->getRepository(\App\Entity\Turno\TurSector::class)->find('COM');
        if(!$arSector){
            $arSector = new \App\Entity\Turno\TurSector();
            $arSector->setCodigoSectorPk('COM');
            $arSector->setNombre('COMERCIAL');
            $arSector->setPorcentaje(8.8);
            $manager->persist($arSector);
        }
        $arSector = $manager->getRepository(\App\Entity\Turno\TurSector::class)->find('RES');
        if(!$arSector){
            $arSector = new \App\Entity\Turno\TurSector();
            $arSector->setCodigoSectorPk('RES');
            $arSector->setNombre('RESIDENCIAL');
            $arSector->setPorcentaje(8.6);
            $manager->persist($arSector);
        }

        $manager->flush();
    }
}
