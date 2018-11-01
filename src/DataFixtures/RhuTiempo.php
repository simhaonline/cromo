<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuTiempo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arTiempo = $manager->getRepository(\App\Entity\RecursoHumano\RhuTiempo::class)->find('TCOMP');
        if(!$arTiempo){
            $arTiempo = new \App\Entity\RecursoHumano\RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TCOMP');
            $arTiempo->setNombre('TIEMPO COMPLETO');
            $arTiempo->setAbreviatura('C');
            $arTiempo->setOrden(1);
            $manager->persist($arTiempo);
        }
        $arTiempo = $manager->getRepository(\App\Entity\RecursoHumano\RhuTiempo::class)->find('TMED');
        if(!$arTiempo){
            $arTiempo = new \App\Entity\RecursoHumano\RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TMED');
            $arTiempo->setNombre('MEDIO TIEMPO');
            $arTiempo->setOrden(2);
            $arTiempo->setAbreviatura('M');
            $manager->persist($arTiempo);
        }
        $arTiempo = $manager->getRepository(\App\Entity\RecursoHumano\RhuTiempo::class)->find('TSAB');
        if(!$arTiempo){
            $arTiempo = new \App\Entity\RecursoHumano\RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TSAB');
            $arTiempo->setNombre('SABATINO');
            $arTiempo->setOrden(3);
            $arTiempo->setAbreviatura('S');
            $manager->persist($arTiempo);
        }
        $manager->flush();
    }
}
