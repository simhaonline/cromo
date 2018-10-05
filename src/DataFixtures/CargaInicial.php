<?php

namespace App\DataFixtures;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenFormaPago;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvDocumentoTipo;
use App\Entity\Transporte\TteConfiguracion;
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
        $arTteConfiguracion= $manager->getRepository('App:Transporte\TteConfiguracion')->find(1);
        if(!$arTteConfiguracion){
            $arTteConfiguracion = new TteConfiguracion();
            $arTteConfiguracion->setCodigoConfiguracionPk(1);
            $arTteConfiguracion->setUsuarioRndc('PENDIENTE');
            $arTteConfiguracion->setEmpresaRndc('PENDIENTE');
            $arTteConfiguracion->setNumeroPoliza(1);
            $arTteConfiguracion->setNumeroIdentificacionAseguradora(1);
            $manager->persist($arTteConfiguracion);
        }
        $arGenConfiguracion= $manager->getRepository('App:General\GenConfiguracion')->find(1);
        if(!$arGenConfiguracion){
            $arGenConfiguracion = new GenConfiguracion();
            $arGenConfiguracion->setCodigoConfiguracionPk(1);
            $arGenConfiguracion->setNit(1);
            $arGenConfiguracion->setDigitoVerificacion(1);
            $arGenConfiguracion->setNombre('PENDIENTE');
            $arGenConfiguracion->setTelefono(1);
            $arGenConfiguracion->setDireccion('PENDIENTE');
            $arGenConfiguracion->setRutaTemporal('PENDIENTE');
            $manager->persist($arGenConfiguracion);
        }
        $arGenFormaPago = $manager->getRepository('App:General\GenFormaPago')->find('CON');
        if(!$arGenFormaPago){
            $arGenFormaPago = new GenFormaPago();
            $arGenFormaPago->setCodigoFormaPagoPk('CON');
            $arGenFormaPago->setNombre('CONTADO');
            $manager->persist($arGenFormaPago);
        }
        $arGenFormaPago = $manager->getRepository('App:General\GenFormaPago')->find('CRE');
        if(!$arGenFormaPago){
            $arGenFormaPago = new GenFormaPago();
            $arGenFormaPago->setCodigoFormaPagoPk('CRE');
            $arGenFormaPago->setNombre('CREDITO');
            $manager->persist($arGenFormaPago);
        }
        $manager->flush();
    }
}
