<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class InvDocumentoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('ENT');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new \App\Entity\Inventario\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('ENT');
            $arDocumentoTipo->setNombre('ENTRADA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('SAL');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new \App\Entity\Inventario\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('SAL');
            $arDocumentoTipo->setNombre('SALIDA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('FAC');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new \App\Entity\Inventario\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('FAC');
            $arDocumentoTipo->setNombre('FACTURA');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('NC');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new \App\Entity\Inventario\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('NC');
            $arDocumentoTipo->setNombre('NOTA CREDITO VENTAS');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('ND');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new \App\Entity\Inventario\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('ND');
            $arDocumentoTipo->setNombre('NOTA DEBITO VENTAS');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('TRA');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new \App\Entity\Inventario\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('TRA');
            $arDocumentoTipo->setNombre('TRASLADO');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('COM');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new \App\Entity\Inventario\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('COM');
            $arDocumentoTipo->setNombre('COMPRA');
            $manager->persist($arDocumentoTipo);
        }
        $manager->flush();
    }
}
