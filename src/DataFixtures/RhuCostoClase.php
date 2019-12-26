<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuCostoClase extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arCostoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuCostoClase::class)->find('ADM');
        if(!$arCostoClase){
            $arCostoClase = new \App\Entity\RecursoHumano\RhuCostoClase();
            $arCostoClase->setCodigoCostoClasePk('ADM');
            $arCostoClase->setNombre('ADMINISTRATIVO');
            $arCostoClase->setOperativo(false);
            $arCostoClase->setOrden(1);
            $manager->persist($arCostoClase);
        }
        $arCostoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuCostoClase::class)->find('COM');
        if(!$arCostoClase){
            $arCostoClase = new \App\Entity\RecursoHumano\RhuCostoClase();
            $arCostoClase->setCodigoCostoClasePk('COM');
            $arCostoClase->setNombre('COMERCIAL');
            $arCostoClase->setOperativo(false);
            $arCostoClase->setOrden(2);
            $manager->persist($arCostoClase);
        }
        $arCostoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuCostoClase::class)->find('OPE');
        if(!$arCostoClase){
            $arCostoClase = new \App\Entity\RecursoHumano\RhuCostoClase();
            $arCostoClase->setCodigoCostoClasePk('OPE');
            $arCostoClase->setNombre('OPERATIVO');
            $arCostoClase->setOperativo(true);
            $arCostoClase->setOrden(3);
            $manager->persist($arCostoClase);
        }
        $arCostoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuCostoClase::class)->find('ADO');
        if(!$arCostoClase){
            $arCostoClase = new \App\Entity\RecursoHumano\RhuCostoClase();
            $arCostoClase->setCodigoCostoClasePk('ADO');
            $arCostoClase->setNombre('ADMINISTRATIVO OPERATIVO');
            $arCostoClase->setOperativo(false);
            $arCostoClase->setOrden(4);
            $manager->persist($arCostoClase);
        }
        $manager->flush();
    }
}
