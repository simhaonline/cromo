<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuPagoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('NOM');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('NOM');
            $arPagoTipo->setNombre('NOMINA');
            $arPagoTipo->setOrden(1);
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('PRI');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('PRI');
            $arPagoTipo->setNombre('PRIMAS');
            $arPagoTipo->setOrden(2);
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('CES');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('CES');
            $arPagoTipo->setNombre('CESANTIAS');
            $arPagoTipo->setOrden(3);
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('VAC');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('VAC');
            $arPagoTipo->setNombre('VACACIONES');
            $arPagoTipo->setOrden(4);
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('LIQ');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('LIQ');
            $arPagoTipo->setNombre('LIQUIDACION');
            $arPagoTipo->setOrden(5);
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('INT');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('INT');
            $arPagoTipo->setNombre('INTERESES CESANTIAS');
            $arPagoTipo->setOrden(6);
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('ANT');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('ANT');
            $arPagoTipo->setNombre('ANTICIPO');
            $arPagoTipo->setOrden(7);
            $manager->persist($arPagoTipo);
        }
        $manager->flush();
    }
}
