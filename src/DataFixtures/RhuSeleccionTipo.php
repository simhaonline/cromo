<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuSeleccionTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arSeleccionTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuSeleccionTipo::class)->find('SDP');
        if (!$arSeleccionTipo) {
            $arSeleccionTipo = new \App\Entity\RecursoHumano\RhuSeleccionTipo();
            $arSeleccionTipo->setCodigoSeleccionTipoPk('SDP');
            $arSeleccionTipo->setNombre('SELECCION DE PERSONAL');
            $manager->persist($arSeleccionTipo);
        }
        $arSeleccionTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuSeleccionTipo::class)->find('SDS');
        if (!$arSeleccionTipo) {
            $arSeleccionTipo = new \App\Entity\RecursoHumano\RhuSeleccionTipo();
            $arSeleccionTipo->setCodigoSeleccionTipoPk('SDS');
            $arSeleccionTipo->setNombre('SERVICIOS DE SELECCION');
            $manager->persist($arSeleccionTipo);
        }
        $arSeleccionTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuSeleccionTipo::class)->find('ADI');
        if (!$arSeleccionTipo) {
            $arSeleccionTipo = new \App\Entity\RecursoHumano\RhuSeleccionTipo();
            $arSeleccionTipo->setCodigoSeleccionTipoPk('ADI');
            $arSeleccionTipo->setNombre('AUTORIZACIÓN DE INGRESO');
            $manager->persist($arSeleccionTipo);
        }
        $manager->flush();
    }
}
