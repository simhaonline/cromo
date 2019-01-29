<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenProceso extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('I');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('I');
            $arGenProcesoTipo->setNombre('INFORME');
            $manager->persist($arGenProcesoTipo);
        }
        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('P');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('P');
            $arGenProcesoTipo->setNombre('PROCESO');
            $manager->persist($arGenProcesoTipo);
        }
        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('U');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('U');
            $arGenProcesoTipo->setNombre('UTILIDAD');
            $manager->persist($arGenProcesoTipo);
        }
        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('O');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('O');
            $arGenProcesoTipo->setNombre('OTROS');
            $manager->persist($arGenProcesoTipo);
        }

        $arrProcesos = array(
            ['codigo' => '0001', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Informe de kardex'],
            ['codigo' => '0002', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Existencia lote'],
            ['codigo' => '0003', 'modulo' => 'Transporte', 'tipo' => 'U', 'nombre' => 'Notificar novedad'],
            ['codigo' => '0004', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Informe de kardex remisión'],
            ['codigo' => '0005', 'modulo' => 'Transporte', 'tipo' => 'U', 'nombre' => 'Corrección de guías'],
            ['codigo' => '0006', 'modulo' => 'Transporte', 'tipo' => 'O', 'nombre' => 'Generar RNDC'],
            ['codigo' => '0007', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Existencia item'],
            ['codigo' => '0008', 'modulo' => 'Inventario', 'tipo' => 'U', 'nombre' => 'Corregir fecha vencimiento lote']

        );
        foreach ($arrProcesos as $arrProceso) {
            $arProceso = $manager->getRepository('App:General\GenProceso')->find($arrProceso['codigo']);
            if(!$arProceso){
                $arProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find($arrProceso['tipo']);
                $arProceso = new \App\Entity\General\GenProceso();
                $arProceso->setCodigoProcesoPk($arrProceso['codigo']);
                $arProceso->setCodigoModuloFk($arrProceso['modulo']);
                $arProceso->setCodigoProcesoTipoFk($arrProceso['tipo']);
                $arProceso->setProcesoTipoRel($arProcesoTipo);
                $arProceso->setNombre($arrProceso['nombre']);
                $manager->persist($arProceso);
            }
        }
        $manager->flush();



    }
}
