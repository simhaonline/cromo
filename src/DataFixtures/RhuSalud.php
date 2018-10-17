<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuSalud extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arSalud = $manager->getRepository(\App\Entity\RecursoHumano\RhuSalud::class)->find('EMP');
        if(!$arSalud){
            $arSalud = new \App\Entity\RecursoHumano\RhuSalud();
            $arSalud->setCodigoSaludPk('EMP');
            $arSalud->setNombre('EMPLEADO');
            $arSalud->setPorcentajeEmpleado(4);
            $arSalud->setPorcentajeEmpleador(8.5);
            $arSalud->setOrden(1);
            $manager->persist($arSalud);
        }
        $arSalud = $manager->getRepository(\App\Entity\RecursoHumano\RhuSalud::class)->find('EMR');
        if(!$arSalud){
            $arSalud = new \App\Entity\RecursoHumano\RhuSalud();
            $arSalud->setCodigoSaludPk('EMR');
            $arSalud->setNombre('EMPLEADOR');
            $arSalud->setPorcentajeEmpleado(0);
            $arSalud->setPorcentajeEmpleador(12.5);
            $arSalud->setOrden(2);
            $manager->persist($arSalud);
        }
        $manager->flush();
    }
}
