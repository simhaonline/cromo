<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenFormaPago extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arGenFormaPago = $manager->getRepository('App:General\GenFormaPago')->find('CON');
        if(!$arGenFormaPago){
            $arGenFormaPago = new \App\Entity\General\GenFormaPago();
            $arGenFormaPago->setCodigoFormaPagoPk('CON');
            $arGenFormaPago->setNombre('CONTADO');
            $manager->persist($arGenFormaPago);
        }
        $arGenFormaPago = $manager->getRepository('App:General\GenFormaPago')->find('CRE');
        if(!$arGenFormaPago){
            $arGenFormaPago = new \App\Entity\General\GenFormaPago();
            $arGenFormaPago->setCodigoFormaPagoPk('CRE');
            $arGenFormaPago->setNombre('CREDITO');
            $manager->persist($arGenFormaPago);
        }
        $manager->flush();
    }
}
