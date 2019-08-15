<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuIncapacidadTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arIncapacidadTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuIncapacidadTipo::class)->find('GEN');
        if(!$arIncapacidadTipo){
            $arIncapacidadTipo = new \App\Entity\RecursoHumano\RhuIncapacidadTipo();
            $arIncapacidadTipo->setCodigoIncapacidadTipoPk('GEN');
            $arIncapacidadTipo->setNombre('INCAPACIDAD GENERAL');
            $arIncapacidadTipo->setGeneraPago(1);
            $arIncapacidadTipo->setGeneraIbc(1);
            $manager->persist($arIncapacidadTipo);
        }
        $manager->flush();

        $arIncapacidadTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuIncapacidadTipo::class)->find('LAB');
        if(!$arIncapacidadTipo){
            $arIncapacidadTipo = new \App\Entity\RecursoHumano\RhuIncapacidadTipo();
            $arIncapacidadTipo->setCodigoIncapacidadTipoPk('LAB');
            $arIncapacidadTipo->setNombre('INCAPACIDAD LABORAL');
            $arIncapacidadTipo->setGeneraPago(1);
            $arIncapacidadTipo->setGeneraIbc(1);
            $manager->persist($arIncapacidadTipo);
        }
        $manager->flush();
    }
}
