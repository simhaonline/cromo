<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenRegimen extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arRegimen = $manager->getRepository(\App\Entity\General\GenRegimen::class)->find('S');
        if(!$arRegimen) {
            $arRegimen = new \App\Entity\General\GenRegimen();
            $arRegimen->setCodigoRegimenPk('S');
            $arRegimen->setNombre('SIMPLE');
            $arRegimen->setCodigoInterface('4');
            $manager->persist($arRegimen);
        }

        $arRegimen = $manager->getRepository(\App\Entity\General\GenRegimen::class)->find('O');
        if(!$arRegimen) {
            $arRegimen = new \App\Entity\General\GenRegimen();
            $arRegimen->setCodigoRegimenPk('O');
            $arRegimen->setNombre('ORDINARIO COMUN');
            $arRegimen->setCodigoInterface('5');
            $manager->persist($arRegimen);
        }

        $manager->flush();
    }
}
