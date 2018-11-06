<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RhuConceptoHora extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('D');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('D');
            $arConceptoHora->setNombre('Horas diurnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('N');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('N');
            $arConceptoHora->setNombre('Horas nocturnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('FD');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('FD');
            $arConceptoHora->setNombre('Festivas diurnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('FN');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('FN');
            $arConceptoHora->setNombre('Festivas nocturnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('EOD');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('EOD');
            $arConceptoHora->setNombre('Extras ordinarias diurnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('EON');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('EON');
            $arConceptoHora->setNombre('Extras ordinarias nocturnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('EFD');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('EFD');
            $arConceptoHora->setNombre('Extras festivas diurnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('EFN');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('EFN');
            $arConceptoHora->setNombre('Extras festivas nocturnas');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('RN');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('RN');
            $arConceptoHora->setNombre('Recargo nocturno');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('RFD');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('RFD');
            $arConceptoHora->setNombre('Recargo festivo diurno');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $arConceptoHora = $manager->getRepository(\App\Entity\RecursoHumano\RhuConceptoHora::class)->find('RFN');
        if(!$arConceptoHora){
            $arConceptoHora = new \App\Entity\RecursoHumano\RhuConceptoHora();
            $arConceptoHora->setCodigoConceptoHoraPk('RFN');
            $arConceptoHora->setNombre('Recargo festivo nocturno');
            $arConceptoHora->setConceptoRel(null);
            $manager->persist($arConceptoHora);
        }
        $manager->flush();
    }
}

