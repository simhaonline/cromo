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
            $arDistribucion->setOrden(3);
            $manager->persist($arDistribucion);
        }
        $manager->flush();
    }
}
