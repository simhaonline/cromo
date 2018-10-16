<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuCargo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0001');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0001');
            $arCargo->setNombre('GERENTE');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0002');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0002');
            $arCargo->setNombre('SUBGERENTE');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0003');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0003');
            $arCargo->setNombre('DIRECTOR ADMINISTRATIVO');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0004');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0004');
            $arCargo->setNombre('SECRETARIA');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0005');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0005');
            $arCargo->setNombre('DIRECTOR FINANCIERO');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0006');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0006');
            $arCargo->setNombre('AUXILIAR DE SISTEMAS');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0007');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0007');
            $arCargo->setNombre('CONTADOR');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0008');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0008');
            $arCargo->setNombre('AUXILIAR ADMINISTRATIVO');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0009');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0009');
            $arCargo->setNombre('APRENDIZ');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0010');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0010');
            $arCargo->setNombre('AUDITOR INTERNO');
            $manager->persist($arCargo);
        }
        $arCargo = $manager->getRepository(\App\Entity\RecursoHumano\RhuCargo::class)->find('0011');
        if(!$arCargo){
            $arCargo = new \App\Entity\RecursoHumano\RhuCargo();
            $arCargo->setCodigoCargoPk('0011');
            $arCargo->setNombre('SUPERVISOR');
            $manager->persist($arCargo);
        }
        $manager->flush();
    }
}
