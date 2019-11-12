<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenFormato extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fecha = date_create('2019-01-01');
        $arGenFormato = $manager->getRepository('App:General\GenFormato')->find(1);
        if(!$arGenFormato){
            $arGenFormato = new \App\Entity\General\GenFormato();
            $arGenFormato->setCodigoFormatoPk(1);
            $arGenFormato->setNombre('ORDEN DE COMPRA');
            $arGenFormato->setFecha($fecha);
            $arGenFormato->setVersion(1);
            $arGenFormato->setCodigoModeloFk('InvOrden');
            $manager->persist($arGenFormato);
        }
        $arGenFormato = $manager->getRepository('App:General\GenFormato')->find(2);
        if(!$arGenFormato){
            $arGenFormato = new \App\Entity\General\GenFormato();
            $arGenFormato->setCodigoFormatoPk(2);
            $arGenFormato->setNombre('HOJA DE VIDA CONDUCTOR');
            $arGenFormato->setFecha($fecha);
            $arGenFormato->setVersion(1);
            $arGenFormato->setCodigoModeloFk('TteConductor');
            $manager->persist($arGenFormato);
        }
        $arGenFormato = $manager->getRepository('App:General\GenFormato')->find(3);
        if(!$arGenFormato){
            $arGenFormato = new \App\Entity\General\GenFormato();
            $arGenFormato->setCodigoFormatoPk(3);
            $arGenFormato->setNombre('COTIZACION');
            $arGenFormato->setFecha($fecha);
            $arGenFormato->setVersion(1);
            $arGenFormato->setCodigoModeloFk('InvCotizacion');
            $manager->persist($arGenFormato);
        }
        $manager->flush();
    }
}
