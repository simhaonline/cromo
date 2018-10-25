<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuCreditoPago extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arCreditoPago = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPago::class)->find('DPN');
        if(!$arCreditoPago){
            $arCreditoPago = new \App\Entity\RecursoHumano\RhuCreditoPago();
            $arCreditoPago->setCodigoCreditoPagoPk('DPN');
            $arCreditoPago->setNombre('DESCUENTO POR NOMINA');
            $manager->persist($arCreditoPago);
        }
        $arCreditoPago = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPago::class)->find('PCION');
        if(!$arCreditoPago){
            $arCreditoPago = new \App\Entity\RecursoHumano\RhuCreditoPago();
            $arCreditoPago->setCodigoCreditoPagoPk('PCION');
            $arCreditoPago->setNombre('PRESTACIONES');
            $manager->persist($arCreditoPago);
        }
        $arCreditoPago = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPago::class)->find('PRES');
        if(!$arCreditoPago){
            $arCreditoPago = new \App\Entity\RecursoHumano\RhuCreditoPago();
            $arCreditoPago->setCodigoCreditoPagoPk('PRES');
            $arCreditoPago->setNombre('PRESTAMO');
            $manager->persist($arCreditoPago);
        }
        $arCreditoPago = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPago::class)->find('VAC');
        if(!$arCreditoPago){
            $arCreditoPago = new \App\Entity\RecursoHumano\RhuCreditoPago();
            $arCreditoPago->setCodigoCreditoPagoPk('VAC');
            $arCreditoPago->setNombre('VACACIONES');
            $manager->persist($arCreditoPago);
        }
        $manager->flush();
    }
}
