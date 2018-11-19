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

        $arrProcesos = array(
            ['codigo' => '0001', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Informe de kardex'],
            ['codigo' => '0002', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Existencia lote'],
            ['codigo' => '0003', 'modulo' => 'Transporte', 'tipo' => 'U', 'nombre' => 'Notificar novedad']);
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
