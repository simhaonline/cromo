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
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('PRI');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('PRI');
            $arPagoTipo->setNombre('PRIMAS');
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('CES');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('CES');
            $arPagoTipo->setNombre('CESANTIAS');
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('VAC');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('VAC');
            $arPagoTipo->setNombre('VACACIONES');
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('LIQ');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('LIQ');
            $arPagoTipo->setNombre('LIQUIDACION');
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('INT');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('INT');
            $arPagoTipo->setNombre('INTERESES CESANTIAS');
            $manager->persist($arPagoTipo);
        }
        $arPagoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuPagoTipo::class)->find('ANT');
        if(!$arPagoTipo){
            $arPagoTipo = new \App\Entity\RecursoHumano\RhuPagoTipo();
            $arPagoTipo->setCodigoPagoTipoPk('ANT');
            $arPagoTipo->setNombre('ANTICIPO');
            $manager->persist($arPagoTipo);
        }
        $manager->flush();
    }
}
