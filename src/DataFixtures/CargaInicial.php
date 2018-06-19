<?php

namespace App\DataFixtures;

use App\Entity\Inventario\InvDocumentoTipo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class CargaInicial extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find(1);
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setNombre('ENTRADA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find(2);
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setNombre('SALIDA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find(3);
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setNombre('FACTURA');
            $manager->persist($arDocumentoTipo);
        }
        $manager->flush();
    }
}
