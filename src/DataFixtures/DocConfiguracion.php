<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class DocConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arDocConfiguracion= $manager->getRepository('App:Documental\DocConfiguracion')->find(1);
        if(!$arDocConfiguracion){
            $arDocConfiguracion = new \App\Entity\Documental\DocConfiguracion();
            $arDocConfiguracion->setCodigoConfiguracionPk(1);
            $arDocConfiguracion->setRutaBandeja('PENDIENTE');
            $arDocConfiguracion->setRutaAlmacenamiento('PENDIENTE');
            $manager->persist($arDocConfiguracion);
        }

        $manager->flush();
    }
}
