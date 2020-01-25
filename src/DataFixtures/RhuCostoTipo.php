<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuCostoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arCostoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCostoTipo::class)->find('FIJ');
        if(!$arCostoTipo){
            $arCostoTipo = new \App\Entity\RecursoHumano\RhuCostoTipo();
            $arCostoTipo->setCodigoCostoTipoPk('FIJ');
            $arCostoTipo->setNombre('FIJO');
            $manager->persist($arCostoTipo);
        }
        $arCostoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCostoTipo::class)->find('DIS');
        if(!$arCostoTipo){
            $arCostoTipo = new \App\Entity\RecursoHumano\RhuCostoTipo();
            $arCostoTipo->setCodigoCostoTipoPk('DIS');
            $arCostoTipo->setNombre('FIJO DISTRIBUIDO');
            $manager->persist($arCostoTipo);
        }
        $arCostoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCostoTipo::class)->find('OPE');
        if(!$arCostoTipo){
            $arCostoTipo = new \App\Entity\RecursoHumano\RhuCostoTipo();
            $arCostoTipo->setCodigoCostoTipoPk('OPE');
            $arCostoTipo->setNombre('OPERATIVO');
            $manager->persist($arCostoTipo);
        }
        $manager->flush();
    }
}
