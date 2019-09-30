<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuVacacionTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arVacacionTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuVacacionTipo::class)->find('GEN');
        if(!$arVacacionTipo){
            $arVacacionTipo = new \App\Entity\RecursoHumano\RhuVacacionTipo();
            $arVacacionTipo->setCodigoVacacionTipoPk('GEN');
            $arVacacionTipo->setNombre('GENERAL');
            $manager->persist($arVacacionTipo);
        }
        $manager->flush();
    }
}
