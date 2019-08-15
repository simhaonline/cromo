<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuEmbargoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arEmbargoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuEmbargoTipo::class)->find('JUD');
        if(!$arEmbargoTipo){
            $arEmbargoTipo = new \App\Entity\RecursoHumano\RhuEmbargoTipo();
            $arEmbargoTipo->setCodigoEmbargoTipoPk('JUD');
            $arEmbargoTipo->setNombre('EMBARGO JUDICIAL');
            $manager->persist($arEmbargoTipo);
        }
        $arEmbargoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuEmbargoTipo::class)->find('COM');
        if(!$arEmbargoTipo){
            $arEmbargoTipo = new \App\Entity\RecursoHumano\RhuEmbargoTipo();
            $arEmbargoTipo->setCodigoEmbargoTipoPk('COM');
            $arEmbargoTipo->setNombre('EMBARGO COMERCIAL');
            $manager->persist($arEmbargoTipo);
        }
        $arEmbargoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuEmbargoTipo::class)->find('ALI');
        if(!$arEmbargoTipo){
            $arEmbargoTipo = new \App\Entity\RecursoHumano\RhuEmbargoTipo();
            $arEmbargoTipo->setCodigoEmbargoTipoPk('ALI');
            $arEmbargoTipo->setNombre('EMBARGO POR ALIMENTOS');
            $manager->persist($arEmbargoTipo);
        }
        $manager->flush();
    }
}
