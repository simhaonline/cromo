<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class GenEstudioTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arEstudioTipo = $manager->getRepository(\App\Entity\General\GenEstudioTipo::class)->find('Bachiller');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\General\GenEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('Bachiller');
            $arEstudioTipo->setNombre('Bachiller');
            $manager->persist($arEstudioTipo);
        }
        $arEstudioTipo = $manager->getRepository(\App\Entity\General\GenEstudioTipo::class)->find('técnico');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\General\GenEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('técnico');
            $arEstudioTipo->setNombre('técnico');
            $manager->persist($arEstudioTipo);
        }
        $arEstudioTipo = $manager->getRepository(\App\Entity\General\GenEstudioTipo::class)->find('tecnologo');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\General\GenEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('tecnologo');
            $arEstudioTipo->setNombre('tecnologo');
            $manager->persist($arEstudioTipo);
        }
        $arEstudioTipo = $manager->getRepository(\App\Entity\General\GenEstudioTipo::class)->find('universitario');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\General\GenEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('universitario');
            $arEstudioTipo->setNombre('universitario');
            $manager->persist($arEstudioTipo);
        }



        $manager->flush();
    }
}
