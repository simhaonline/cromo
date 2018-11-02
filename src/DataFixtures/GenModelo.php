<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenModelo extends Fixture
{
    public function load(ObjectManager $manager )
    {
        $arModelo = $manager->getRepository(\App\Entity\General\GenModelo::class)->find('TteDespacho');
        if(!$arModelo) {
            $arModelo = new \App\Entity\General\GenModelo();
            $arModelo->setCodigoModeloPk('TteDespacho');
            $arModelo->setCodigoModuloFk('Transporte');
            $manager->persist($arModelo);
        }

        $arModelo = $manager->getRepository(\App\Entity\General\GenModelo::class)->find('FinAsiento');
        if(!$arModelo) {
            $arModelo = new \App\Entity\General\GenModelo();
            $arModelo->setCodigoModeloPk('FinAsiento');
            $arModelo->setCodigoModuloFk('Financiero');
            $manager->persist($arModelo);
        }
        $manager->flush();
    }
}
