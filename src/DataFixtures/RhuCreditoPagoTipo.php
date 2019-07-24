<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuCreditoPagoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arCreditoPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPagoTipo::class)->find('NOM');
        if(!$arCreditoPagoTipo){
            $arCreditoPagoTipo = new \App\Entity\RecursoHumano\RhuCreditoPagoTipo();
            $arCreditoPagoTipo->setCodigoCreditoPagoTipoPk('NOM');
            $arCreditoPagoTipo->setNombre('DESCUENTO POR NOMINA');
            $manager->persist($arCreditoPagoTipo);
        }
        $arCreditoPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPagoTipo::class)->find('PRE');
        if(!$arCreditoPagoTipo){
            $arCreditoPagoTipo = new \App\Entity\RecursoHumano\RhuCreditoPagoTipo();
            $arCreditoPagoTipo->setCodigoCreditoPagoTipoPk('PRE');
            $arCreditoPagoTipo->setNombre('PRESTAMO LIBRE');
            $manager->persist($arCreditoPagoTipo);
        }
        $arCreditoPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPagoTipo::class)->find('VAC');
        if(!$arCreditoPagoTipo){
            $arCreditoPagoTipo = new \App\Entity\RecursoHumano\RhuCreditoPagoTipo();
            $arCreditoPagoTipo->setCodigoCreditoPagoTipoPk('VAC');
            $arCreditoPagoTipo->setNombre('VACACIONES');
            $manager->persist($arCreditoPagoTipo);
        }
        $arCreditoPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCreditoPagoTipo::class)->find('PRS');
        if(!$arCreditoPagoTipo){
            $arCreditoPagoTipo = new \App\Entity\RecursoHumano\RhuCreditoPagoTipo();
            $arCreditoPagoTipo->setCodigoCreditoPagoTipoPk('PRS');
            $arCreditoPagoTipo->setNombre('PRESTACIONES');
            $manager->persist($arCreditoPagoTipo);
        }
        $manager->flush();
    }
}
