<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class RhuEstudioTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arEstudioTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuEstudioTipo::class)->find('Bachiller');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\RecursoHumano\RhuEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('Bachiller');
            $arEstudioTipo->setNombre('Bachiller');
            $arEstudioTipo->setValidarVencimiento(true);
            $manager->persist($arEstudioTipo);
        }
        $arEstudioTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuEstudioTipo::class)->find('técnico');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\RecursoHumano\RhuEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('técnico');
            $arEstudioTipo->setNombre('técnico');
            $arEstudioTipo->setValidarVencimiento(true);
            $manager->persist($arEstudioTipo);
        }
        $arEstudioTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuEstudioTipo::class)->find('tecnologo');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\RecursoHumano\RhuEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('tecnologo');
            $arEstudioTipo->setNombre('tecnologo');
            $arEstudioTipo->setValidarVencimiento(true);
            $manager->persist($arEstudioTipo);
        }
        $arEstudioTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuEstudioTipo::class)->find('universitario');
        if(!$arEstudioTipo){
            $arEstudioTipo = new \App\Entity\RecursoHumano\RhuEstudioTipo();
            $arEstudioTipo->setCodigoEstudioTipoPk('universitario');
            $arEstudioTipo->setNombre('universitario');
            $arEstudioTipo->setValidarVencimiento(true);
            $manager->persist($arEstudioTipo);
        }



        $manager->flush();
    }
}
