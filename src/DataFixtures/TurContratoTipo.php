<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class TurContratoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arContratoTipo = $manager->getRepository(\App\Entity\Turno\TurContratoTipo::class)->find('PER');
        if(!$arContratoTipo){
            $arContratoTipo = new \App\Entity\Turno\TurContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('PER');
            $arContratoTipo->setNombre('PERMANENTE');
            $manager->persist($arContratoTipo);
        }
        $manager->flush();
    }
}
