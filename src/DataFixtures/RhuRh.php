<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuRh extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(1);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(1);
            $arRhu->setNombre('O-');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(2);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(2);
            $arRhu->setNombre('O+');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(3);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(3);
            $arRhu->setNombre('A-');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(4);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(4);
            $arRhu->setNombre('A+');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(5);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(5);
            $arRhu->setNombre('B-');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(6);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(6);
            $arRhu->setNombre('B+');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(7);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(7);
            $arRhu->setNombre('AB-');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(8);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(8);
            $arRhu->setNombre('AB+');
            $manager->persist($arRhu);
        }
        $arRhu = $manager->getRepository(\App\Entity\RecursoHumano\RhuRh::class)->find(9);
        if(!$arRhu){
            $arRhu = new \App\Entity\RecursoHumano\RhuRh();
            $arRhu->setCodigoRhPk(9);
            $arRhu->setNombre('SIN DEFINIR');
            $manager->persist($arRhu);
        }
        $manager->flush();
    }
}
