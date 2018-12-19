<?php

namespace App\DataFixtures;

use App\Entity\General\GenTarea;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenTareaPrioridad extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arTareaPrioridad = $manager->getRepository(GenTarea::class)->find('CRI');
        if(!$arTareaPrioridad){
            $arTareaPrioridad = new \App\Entity\General\GenTareaPrioridad();
            $arTareaPrioridad->setCodigoTareaPrioridadPk('CRI');
            $arTareaPrioridad->setNombre('CRITICO');
            $arTareaPrioridad->setColor('#830000');
            $arTareaPrioridad->setIcono('fa fa-warning');
            $manager->persist($arTareaPrioridad);
        }
        $arTareaPrioridad = $manager->getRepository(GenTarea::class)->find('IMP');
        if(!$arTareaPrioridad){
            $arTareaPrioridad = new \App\Entity\General\GenTareaPrioridad();
            $arTareaPrioridad->setCodigoTareaPrioridadPk('IMP');
            $arTareaPrioridad->setNombre('IMPORTANTE');
            $arTareaPrioridad->setColor('#ffcc00');
            $arTareaPrioridad->setIcono('fa fa-exclamation');
            $manager->persist($arTareaPrioridad);
        }
        $manager->flush();
    }
}