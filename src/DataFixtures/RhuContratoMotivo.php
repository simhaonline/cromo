<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuContratoMotivo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('TCO');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('TCO');
            $arContratoMotivo->setMotivo('TERMINACIÓN DE CONTRATO');
            $manager->persist($arContratoMotivo);
        }
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('RVO');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('RVO');
            $arContratoMotivo->setMotivo('RENUNCIA VOLUNTARIA');
            $manager->persist($arContratoMotivo);
        }
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('MAC');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('MAC');
            $arContratoMotivo->setMotivo('MUTUO ACUERDO');
            $manager->persist($arContratoMotivo);
        }
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('SJC');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('SJC');
            $arContratoMotivo->setMotivo('SIN JUSTA CAUSA');
            $manager->persist($arContratoMotivo);
        }
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('CJC');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('CJC');
            $arContratoMotivo->setMotivo('CON JUSTA CAUSA');
            $manager->persist($arContratoMotivo);
        }
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('JUB');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('JUB');
            $arContratoMotivo->setMotivo('JUBILACIÓN');
            $manager->persist($arContratoMotivo);
        }
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('MUE');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('MUE');
            $arContratoMotivo->setMotivo('MUERTE');
            $manager->persist($arContratoMotivo);
        }
        $arContratoMotivo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoMotivo::class)->find('INA');
        if(!$arContratoMotivo){
            $arContratoMotivo = new \App\Entity\RecursoHumano\RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('INA');
            $arContratoMotivo->setMotivo('INACTIVO POR EL USUARIO');
            $manager->persist($arContratoMotivo);
        }

        $manager->flush();
    }
}
