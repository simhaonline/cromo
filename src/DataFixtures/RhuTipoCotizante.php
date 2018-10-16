<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuTipoCotizante extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('1');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('1');
            $arTipoCotizante->setNombre('Dependiente');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('2');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('2');
            $arTipoCotizante->setNombre('Servicio Doméstico');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('3');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('3');
            $arTipoCotizante->setNombre('Independiente');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('4');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('4');
            $arTipoCotizante->setNombre('Madre Comunitaria');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('12');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('12');
            $arTipoCotizante->setNombre('Aprendiz SENA en etapa lectiva');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('15');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('15');
            $arTipoCotizante->setNombre('Desempleado con subsidio de caja de compensación familiar');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('16');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('16');
            $arTipoCotizante->setNombre('Independiente agremiado o asociado');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('18');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('18');
            $arTipoCotizante->setNombre('Funcionarios públicos sin tope máximo en el IBC');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('19');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('19');
            $arTipoCotizante->setNombre('Aprendices SENA en etapa productiva');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('20');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('20');
            $arTipoCotizante->setNombre('Estudiantes (Régimen especial-Ley 789/2002)');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('21');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('21');
            $arTipoCotizante->setNombre('Estudiantes de postgrado en salud (Decreto 190 de 1996)');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('22');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('22');
            $arTipoCotizante->setNombre('Profesor de establecimiento particular');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('23');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('23');
            $arTipoCotizante->setNombre('Estudiantes (Decreto 055 de 2015)');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('30');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('30');
            $arTipoCotizante->setNombre('Dependiente entidades o universidades públicas con régimen especial en Salud');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('31');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('31');
            $arTipoCotizante->setNombre('Cooperados o precooperativas de trabajo asociado');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('32');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('32');
            $arTipoCotizante->setNombre('Cotizante miembro de la carrera diplomática o consular de un país extranjero o funcionario de organismo multilateral no sometido a la legislación c');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('33');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('33');
            $arTipoCotizante->setNombre('Beneficiario del fondo de solidaridad pensional');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(\App\Entity\RecursoHumano\RhuTipoCotizante::class)->find('34');
        if(!$arTipoCotizante){
            $arTipoCotizante = new \App\Entity\RecursoHumano\RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('34');
            $arTipoCotizante->setNombre('Concejal amparado por póliza de salud');
            $manager->persist($arTipoCotizante);
        }

        $manager->flush();
    }
}
