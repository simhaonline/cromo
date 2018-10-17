<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TteConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arTteConfiguracion= $manager->getRepository('App:Transporte\TteConfiguracion')->find(1);
        if(!$arTteConfiguracion){
            $arTteConfiguracion = new \App\Entity\Transporte\TteConfiguracion();
            $arTteConfiguracion->setCodigoConfiguracionPk(1);
            $arTteConfiguracion->setUsuarioRndc('PENDIENTE');
            $arTteConfiguracion->setEmpresaRndc('PENDIENTE');
            $arTteConfiguracion->setNumeroPoliza(1);
            $arTteConfiguracion->setNumeroIdentificacionAseguradora(1);
            $manager->persist($arTteConfiguracion);
        }
        $manager->flush();
    }
}