<?php

namespace App\DataFixtures;

use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvDocumentoTipo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class CargaInicial extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('ENT');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('ENT');
            $arDocumentoTipo->setNombre('ENTRADA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('SAL');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('SAL');
            $arDocumentoTipo->setNombre('SALIDA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('FAC');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('FAC');
            $arDocumentoTipo->setNombre('FACTURA');
            $manager->persist($arDocumentoTipo);
        }
        $arInvConfiguracion = $manager->getRepository('App:Inventario\InvConfiguracion')->find(1);
        if(!$arInvConfiguracion){
            $arInvConfiguracion = new InvConfiguracion();
            $arInvConfiguracion->setCodigoConfiguracionPk(1);
            $arInvConfiguracion->setCodigoFormatoMovimiento(1);
            $manager->persist($arInvConfiguracion);
        }
        $manager->flush();
    }
}
