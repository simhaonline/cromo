<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuContratoClase extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arContratoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoClase::class)->find('OBL');
        if(!$arContratoClase){
            $arContratoClase = new \App\Entity\RecursoHumano\RhuContratoClase();
            $arContratoClase->setCodigoContratoClasePk('OBL');
            $arContratoClase->setNombre('OBRA O LABOR');
            $arContratoClase->setIndefinido(0);
            $manager->persist($arContratoClase);
        }
        $arContratoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoClase::class)->find('FIJ');
        if(!$arContratoClase){
            $arContratoClase = new \App\Entity\RecursoHumano\RhuContratoClase();
            $arContratoClase->setCodigoContratoClasePk('FIJ');
            $arContratoClase->setNombre('FIJO');
            $arContratoClase->setIndefinido(0);
            $manager->persist($arContratoClase);
        }
        $arContratoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoClase::class)->find('IND');
        if(!$arContratoClase){
            $arContratoClase = new \App\Entity\RecursoHumano\RhuContratoClase();
            $arContratoClase->setCodigoContratoClasePk('IND');
            $arContratoClase->setNombre('INDEFINIDO');
            $arContratoClase->setIndefinido(1);
            $manager->persist($arContratoClase);
        }
        $arContratoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoClase::class)->find('APR');
        if(!$arContratoClase){
            $arContratoClase = new \App\Entity\RecursoHumano\RhuContratoClase();
            $arContratoClase->setCodigoContratoClasePk('APR');
            $arContratoClase->setNombre('APRENDIZ');
            $arContratoClase->setIndefinido(0);
            $manager->persist($arContratoClase);
        }
        $arContratoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoClase::class)->find('PRA');
        if(!$arContratoClase){
            $arContratoClase = new \App\Entity\RecursoHumano\RhuContratoClase();
            $arContratoClase->setCodigoContratoClasePk('PRA');
            $arContratoClase->setNombre('PRACTICANTE');
            $arContratoClase->setIndefinido(0);
            $manager->persist($arContratoClase);
        }
        $arContratoClase = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoClase::class)->find('PDS');
        if(!$arContratoClase){
            $arContratoClase = new \App\Entity\RecursoHumano\RhuContratoClase();
            $arContratoClase->setCodigoContratoClasePk('PDS');
            $arContratoClase->setNombre('PRESTACION DE SERVICIOS');
            $arContratoClase->setIndefinido(0);
            $manager->persist($arContratoClase);
        }
        $manager->flush();
    }
}
