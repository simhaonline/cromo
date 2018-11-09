<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenMoneda extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arGenMoneda = $manager->getRepository('App:General\GenMoneda')->find('COP');
        if(!$arGenMoneda){
            $arGenMoneda = new \App\Entity\General\GenMoneda();
            $arGenMoneda->setCodigoMonedaPk('COP');
            $arGenMoneda->setNombre('PESO COLOMBIANO');
            $manager->persist($arGenMoneda);
        }
        $arGenMoneda = $manager->getRepository('App:General\GenMoneda')->find('USD');
        if(!$arGenMoneda){
            $arGenMoneda = new \App\Entity\General\GenMoneda();
            $arGenMoneda->setCodigoMonedaPk('USD');
            $arGenMoneda->setNombre('DOLAR ESTADOUNIDENSE');
            $manager->persist($arGenMoneda);
        }
        $arGenMoneda = $manager->getRepository('App:General\GenMoneda')->find('EUR');
        if(!$arGenMoneda){
            $arGenMoneda = new \App\Entity\General\GenMoneda();
            $arGenMoneda->setCodigoMonedaPk('EUR');
            $arGenMoneda->setNombre('EURO');
            $manager->persist($arGenMoneda);
        }
        $manager->flush();
    }
}
