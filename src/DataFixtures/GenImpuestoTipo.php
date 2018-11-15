<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenImpuestoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arGenImpuestoTipo = $manager->getRepository('App:General\GenImpuestoTipo')->find('R');
        if(!$arGenImpuestoTipo){
            $arGenImpuestoTipo = new \App\Entity\General\GenImpuestoTipo();
            $arGenImpuestoTipo->setCodigoImpuestoTipoPk('R');
            $arGenImpuestoTipo->setNombre('RETENCION');
            $manager->persist($arGenImpuestoTipo);
        }
        $arGenImpuestoTipo = $manager->getRepository('App:General\GenImpuestoTipo')->find('I');
        if(!$arGenImpuestoTipo){
            $arGenImpuestoTipo = new \App\Entity\General\GenImpuestoTipo();
            $arGenImpuestoTipo->setCodigoImpuestoTipoPk('I');
            $arGenImpuestoTipo->setNombre('IVA');
            $manager->persist($arGenImpuestoTipo);
        }
        $manager->flush();

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
