<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuCargoSupervisor extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(1);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('VIGILANTES');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(2);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('ESCOLTAS');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(3);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('OPERADOR MEDIOS');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(4);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('SUPERVISOR');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(5);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('DIRECTIVO');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(6);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('GERENTE');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(7);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('SECRETARIA');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(8);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('INVESTIGADOR');
            $manager->persist($arCargoSupervigilancia);
        }
        $arCargoSupervigilancia = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargoSupervigilancia::class)->find(9);
        if(!$arCargoSupervigilancia){
            $arCargoSupervigilancia = new \App\Entity\RecursoHumano\RhuCargoSupervigilancia();
            $arCargoSupervigilancia->setNombre('OTROS');
            $manager->persist($arCargoSupervigilancia);
        }
        $manager->flush();
    }
}
