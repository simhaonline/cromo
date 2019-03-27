<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenCalidadFormato extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fecha = date_create('2019-01-01');
        $arGenCalidadFormato = $manager->getRepository('App:General\GenCalidadFormato')->find(1);
        if(!$arGenCalidadFormato){
            $arGenCalidadFormato = new \App\Entity\General\GenCalidadFormato();
            $arGenCalidadFormato->setNombre('ORDEN DE COMPRA');
            $arGenCalidadFormato->setFecha($fecha);
            $arGenCalidadFormato->setVersion(1);
            $arGenCalidadFormato->setCodigoModeloFk('InvOrden');
            $manager->persist($arGenCalidadFormato);
        }
        $arGenCalidadFormato = $manager->getRepository('App:General\GenCalidadFormato')->find(2);
        if(!$arGenCalidadFormato){
            $arGenCalidadFormato = new \App\Entity\General\GenCalidadFormato();
            $arGenCalidadFormato->setNombre('HOJA DE VIDA CONDUCTOR');
            $arGenCalidadFormato->setFecha($fecha);
            $arGenCalidadFormato->setVersion(1);
            $arGenCalidadFormato->setCodigoModeloFk('TteConductor');
            $manager->persist($arGenCalidadFormato);
        }
        $arGenCalidadFormato = $manager->getRepository('App:General\GenCalidadFormato')->find(3);
        if(!$arGenCalidadFormato){
            $arGenCalidadFormato = new \App\Entity\General\GenCalidadFormato();
            $arGenCalidadFormato->setNombre('COTIZACION');
            $arGenCalidadFormato->setFecha($fecha);
            $arGenCalidadFormato->setVersion(1);
            $arGenCalidadFormato->setCodigoModeloFk('InvCotizacion');
            $manager->persist($arGenCalidadFormato);
        }
        $manager->flush();
    }
}
