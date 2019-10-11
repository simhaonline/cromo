<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenTipoPersona extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arTipoPersona = $manager->getRepository(\App\Entity\General\GenTipoPersona::class)->find('J');
        if(!$arTipoPersona) {
            $arTipoPersona = new \App\Entity\General\GenTipoPersona();
            $arTipoPersona->setCodigoTipoPersonaPk('J');
            $arTipoPersona->setNombre('JURIDICA');
            $arTipoPersona->setCodigoInterface('1');
            $manager->persist($arTipoPersona);
        }

        $arTipoPersona = $manager->getRepository(\App\Entity\General\GenTipoPersona::class)->find('N');
        if(!$arTipoPersona) {
            $arTipoPersona = new \App\Entity\General\GenTipoPersona();
            $arTipoPersona->setCodigoTipoPersonaPk('N');
            $arTipoPersona->setNombre('NATURAL');
            $arTipoPersona->setCodigoInterface('2');
            $manager->persist($arTipoPersona);
        }
        $manager->flush();
    }
}
