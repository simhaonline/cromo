<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuContratoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arContratoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoTipo::class)->find('OBR');
        if(!$arContratoTipo){
            $arContratoTipo = new \App\Entity\RecursoHumano\RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('OBR');
            $arContratoTipo->setNombre('CONTRATO POR OBRA O LABOR CONTRATADA');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoTipo::class)->find('FIJ');
        if(!$arContratoTipo){
            $arContratoTipo = new \App\Entity\RecursoHumano\RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('FIJ');
            $arContratoTipo->setNombre('CONTRATO INDIVIDUAL DE TRABAJO A TERMINO FIJO INFERIOR A UN ANIO');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoTipo::class)->find('IND');
        if(!$arContratoTipo){
            $arContratoTipo = new \App\Entity\RecursoHumano\RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('IND');
            $arContratoTipo->setNombre('CONTRATO INDEFINIDO');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoTipo::class)->find('APR');
        if(!$arContratoTipo){
            $arContratoTipo = new \App\Entity\RecursoHumano\RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('APR');
            $arContratoTipo->setNombre('CONTRATO POR APRENDIZ DEL SENA');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoTipo::class)->find('PRA');
        if(!$arContratoTipo){
            $arContratoTipo = new \App\Entity\RecursoHumano\RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('PRA');
            $arContratoTipo->setNombre('CONTRATO DE PRACTICA ESTUDIANTIL');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(\App\Entity\RecursoHumano\RhuContratoTipo::class)->find('PRE');
        if(!$arContratoTipo){
            $arContratoTipo = new \App\Entity\RecursoHumano\RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('PRE');
            $arContratoTipo->setNombre('CONTRATO POR PRESTACIÓN DE SERVICIO');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }

        $manager->flush();
    }
}
