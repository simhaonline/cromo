<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuTiempo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arTiempo = $manager->getRepository(RhuTiempo::class)->find('TCOMP');
        if(!$arTiempo){
            $arTiempo = new \App\Entity\RecursoHumano\RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TCOMP');
            $arTiempo->setNombre('TIEMPO COMPLETO');
            $arTiempo->setAbreviatura('C');
            $manager->persist($arTiempo);
        }
        $arTiempo = $manager->getRepository(RhuTiempo::class)->find('TMED');
        if(!$arTiempo){
            $arTiempo = new \App\Entity\RecursoHumano\RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TMED');
            $arTiempo->setNombre('MEDIO TIEMPO');
            $arTiempo->setAbreviatura('M');
            $manager->persist($arTiempo);
        }
        $arTiempo = $manager->getRepository(RhuTiempo::class)->find('TSAB');
        if(!$arTiempo){
            $arTiempo = new \App\Entity\RecursoHumano\RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TSAB');
            $arTiempo->setNombre('SABATINO');
            $arTiempo->setAbreviatura('S');
            $manager->persist($arTiempo);
        }

        $manager->flush();
    }
}
