<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenImpuesto extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arGenImpuesto = $manager->getRepository('App:General\GenImpuesto')->find('R00');
        if(!$arGenImpuesto){
            $arGenImpuesto = new \App\Entity\General\GenImpuesto();
            $arGenImpuesto->setCodigoImpuestoPk('R00');
            $arGenImpuesto->setCodigoImpuestoTipoFk('R');
            $arGenImpuesto->setNombre('SIN RETENCION');
            $manager->persist($arGenImpuesto);
        }
        $arGenImpuesto = $manager->getRepository('App:General\GenImpuesto')->find('I00');
        if(!$arGenImpuesto){
            $arGenImpuesto = new \App\Entity\General\GenImpuesto();
            $arGenImpuesto->setCodigoImpuestoPk('I00');
            $arGenImpuesto->setCodigoImpuestoTipoFk('I');
            $arGenImpuesto->setNombre('SIN IVA');
            $manager->persist($arGenImpuesto);
        }
        $manager->flush();
    }
}
