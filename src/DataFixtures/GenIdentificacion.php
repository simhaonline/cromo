<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenIdentificacion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('CC');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('CC');
            $arIdentificacion->setNombre('CEDULA DE CIUDADANIA');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('RC');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('RC');
            $arIdentificacion->setNombre('REGISTRO CIVIL DE NACIMIENTO');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('TI');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('TI');
            $arIdentificacion->setNombre('TARJETA DE IDENTIDAD');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('TE');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('TE');
            $arIdentificacion->setNombre('TARJETA DE EXTRANJERÍA');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('CE');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('CE');
            $arIdentificacion->setNombre('CÉDULA DE EXTRANJERÍA');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('NI');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('NI');
            $arIdentificacion->setNombre('NIT');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('PE');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('PE');
            $arIdentificacion->setNombre('PASAPORTE');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('TDE');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('TDE');
            $arIdentificacion->setNombre('TIPO DOCUMENTO EXTRANJERO');
            $manager->persist($arIdentificacion);
        }

        $arIdentificacion = $manager->getRepository(\App\Entity\General\GenIdentificacion::class)->find('PE');
        if(!$arIdentificacion) {
            $arIdentificacion = new \App\Entity\General\GenIdentificacion();
            $arIdentificacion->setCodigoIdentificacionPk('PE');
            $arIdentificacion->setNombre('PERMISO ESPECIAL DE PERMANENCIA');
            $manager->persist($arIdentificacion);
        }

        $manager->flush();
    }
}
