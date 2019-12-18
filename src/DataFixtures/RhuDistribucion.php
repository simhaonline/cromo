<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuDistribucion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arDistribucion = $manager->getRepository(\App\Entity\RecursoHumano\RhuDistribucion::class)->find('IN001');
        if(!$arDistribucion){
            $arDistribucion = new \App\Entity\RecursoHumano\RhuDistribucion();
            $arDistribucion->setCodigoDistribucionPk('IN001');
            $arDistribucion->setNombre('DISTRIBUCION 1');
            $arDistribucion->setOrden(1);
            $manager->persist($arDistribucion);
        }

        $arDistribucion = $manager->getRepository(\App\Entity\RecursoHumano\RhuDistribucion::class)->find('SU001');
        if(!$arDistribucion){
            $arDistribucion = new \App\Entity\RecursoHumano\RhuDistribucion();
            $arDistribucion->setCodigoDistribucionPk('SU001');
            $arDistribucion->setNombre('DISTRIBUCION 2');
            $arDistribucion->setOrden(2);
            $manager->persist($arDistribucion);
        }

        $arDistribucion = $manager->getRepository(\App\Entity\RecursoHumano\RhuDistribucion::class)->find('DP001');
        if(!$arDistribucion){
            $arDistribucion = new \App\Entity\RecursoHumano\RhuDistribucion();
            $arDistribucion->setCodigoDistribucionPk('DP001');
            $arDistribucion->setNombre('DISTRIBUCION 3');
            $arDistribucion->setOrden(3);
            $manager->persist($arDistribucion);
        }

        $arDistribucion = $manager->getRepository(\App\Entity\RecursoHumano\RhuDistribucion::class)->find('ESSU1');
        if(!$arDistribucion){
            $arDistribucion = new \App\Entity\RecursoHumano\RhuDistribucion();
            $arDistribucion->setCodigoDistribucionPk('ESSU1');
            $arDistribucion->setNombre('DISTRIBUCION 4');
            $arDistribucion->setOrden(4);
            $manager->persist($arDistribucion);
        }

        $arDistribucion = $manager->getRepository(\App\Entity\RecursoHumano\RhuDistribucion::class)->find('SER01');
        if(!$arDistribucion){
            $arDistribucion = new \App\Entity\RecursoHumano\RhuDistribucion();
            $arDistribucion->setCodigoDistribucionPk('SER01');
            $arDistribucion->setNombre('DISTRIBUCION 5');
            $arDistribucion->setOrden(5);
            $manager->persist($arDistribucion);
        }

        $arDistribucion = $manager->getRepository(\App\Entity\RecursoHumano\RhuDistribucion::class)->find('1TE01');
        if(!$arDistribucion){
            $arDistribucion = new \App\Entity\RecursoHumano\RhuDistribucion();
            $arDistribucion->setCodigoDistribucionPk('1TE01');
            $arDistribucion->setNombre('DISTRIBUCION 6');
            $arDistribucion->setOrden(6);
            $manager->persist($arDistribucion);
        }

        $arDistribucion = $manager->getRepository(\App\Entity\RecursoHumano\RhuDistribucion::class)->find('DP002');
        if(!$arDistribucion){
            $arDistribucion = new \App\Entity\RecursoHumano\RhuDistribucion();
            $arDistribucion->setCodigoDistribucionPk('DP002');
            $arDistribucion->setNombre('DISTRIBUCION 7');
            $arDistribucion->setOrden(7);
            $manager->persist($arDistribucion);
        }
        $manager->flush();
    }
}
