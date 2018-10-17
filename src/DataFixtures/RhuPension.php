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
            $arPension->setOrden(1);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('ALT');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('ALT');
            $arPension->setNombre('ALTO RIESGO');
            $arPension->setPorcentajeEmpleado(4);
            $arPension->setPorcentajeEmpleador(22);
            $arPension->setOrden(2);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('EMN');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('EMN');
            $arPension->setNombre('EMPLEADOR NORMAL');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(16);
            $arPension->setOrden(3);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('EMA');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('EMA');
            $arPension->setNombre('EMPLEADOR ALTO RIESGO');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(26);
            $arPension->setOrden(4);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(\App\Entity\RecursoHumano\RhuPension::class)->find('PEN');
        if(!$arPension){
            $arPension = new \App\Entity\RecursoHumano\RhuPension();
            $arPension->setCodigoPensionPk('PEN');
            $arPension->setNombre('PENSIONADO');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(0);
            $arPension->setOrden(5);
            $manager->persist($arPension);
        }

        $manager->flush();
    }
}
