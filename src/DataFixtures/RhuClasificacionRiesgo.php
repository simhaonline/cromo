<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuClasificacionRiesgo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arClasificacionRiesgo = $manager->getRepository(\App\Entity\RecursoHumano\RhuClasificacionRiesgo::class)->find('I');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new \App\Entity\RecursoHumano\RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('I');
            $arClasificacionRiesgo->setNombre('I - 0.522');
            $arClasificacionRiesgo->setPorcentaje(0.522);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(\App\Entity\RecursoHumano\RhuClasificacionRiesgo::class)->find('II');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new \App\Entity\RecursoHumano\RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('II');
            $arClasificacionRiesgo->setNombre('II - 1.044');
            $arClasificacionRiesgo->setPorcentaje(1.044);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(\App\Entity\RecursoHumano\RhuClasificacionRiesgo::class)->find('III');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new \App\Entity\RecursoHumano\RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('III');
            $arClasificacionRiesgo->setNombre('III - 2.436');
            $arClasificacionRiesgo->setPorcentaje(2.436);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(\App\Entity\RecursoHumano\RhuClasificacionRiesgo::class)->find('IV');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new \App\Entity\RecursoHumano\RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('IV');
            $arClasificacionRiesgo->setNombre('IV - 4.350');
            $arClasificacionRiesgo->setPorcentaje(4.35);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(\App\Entity\RecursoHumano\RhuClasificacionRiesgo::class)->find('V');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new \App\Entity\RecursoHumano\RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('V');
            $arClasificacionRiesgo->setNombre('V - 6.960');
            $arClasificacionRiesgo->setPorcentaje(6.96);
            $manager->persist($arClasificacionRiesgo);
        }

        $manager->flush();
    }
}