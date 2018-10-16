<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuPension extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('NOR');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('NOR');
            $arPension->setNombre('NORMAL');
            $arPension->setPorcentajeEmpleado(4);
            $arPension->setPorcentajeEmpleador(12);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('ALT');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('ALT');
            $arPension->setNombre('ALTO RIESGO');
            $arPension->setPorcentajeEmpleado(4);
            $arPension->setPorcentajeEmpleador(22);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('EMN');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('EMN');
            $arPension->setNombre('EMPLEADOR NORMAL');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(16);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('EMA');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('EMA');
            $arPension->setNombre('EMPLEADOR ALTO RIESGO');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(26);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('PEN');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('PEN');
            $arPension->setNombre('PENSIONADO');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(0);
            $manager->persist($arPension);
        }

        $manager->flush();
    }
}
