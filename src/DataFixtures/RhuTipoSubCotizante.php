<?php

namespace App\DataFixtures;

use App\Entity\RecursoHumano\RhuSubtipoCotizante;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuTipoSubCotizante extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('0');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('0');
            $arSubtipoCotizante->setNombre('SIN PENSIONAR');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('1');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('1');
            $arSubtipoCotizante->setNombre('Dependiente pensionado por vejez activo (SI no es pensionado es = a 00)');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('2');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('2');
            $arSubtipoCotizante->setNombre('Independiente pensionado por vejez activo');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('3');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('3');
            $arSubtipoCotizante->setNombre('Cotizante no obligado a cotización a pensiones por edad');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('4');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('4');
            $arSubtipoCotizante->setNombre('Cotizante con requisitos cumplidos para pensión.');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('5');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('5');
            $arSubtipoCotizante->setNombre('Cotizante a quien se le ha reconocido indemnización sustitutiva o devolución de saldos');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('6');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('6');
            $arSubtipoCotizante->setNombre('Cotizante perteneciente a un régimen exceptuado de pensiones o a entidades autorizadas para recibir aportes exclusivamente de un grupo de sus propio');
            $manager->persist($arSubtipoCotizante);
        }

        $manager->flush();
    }
}
