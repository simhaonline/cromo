<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenFormato extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fecha = date_create('2019-01-01');
        $arGenFormato = $manager->getRepository('App:General\GenFormato')->find(1);
        if(!$arGenFormato){
            $arGenFormato = new \App\Entity\General\GenFormato();
            $arGenFormato->setCodigoFormatoPk(1);
            $arGenFormato->setNombre('ORDEN DE COMPRA');
            $arGenFormato->setFecha($fecha);
            $arGenFormato->setVersion(1);
            $arGenFormato->setCodigoModeloFk('InvOrden');
            $manager->persist($arGenFormato);
        }
        $arGenFormato = $manager->getRepository('App:General\GenFormato')->find(2);
        if(!$arGenFormato){
            $arGenFormato = new \App\Entity\General\GenFormato();
            $arGenFormato->setCodigoFormatoPk(2);
            $arGenFormato->setNombre('HOJA DE VIDA CONDUCTOR');
            $arGenFormato->setFecha($fecha);
            $arGenFormato->setVersion(1);
            $arGenFormato->setCodigoModeloFk('TteConductor');
            $manager->persist($arGenFormato);
        }
        $arGenFormato = $manager->getRepository('App:General\GenFormato')->find(3);
        if(!$arGenFormato){
            $arGenFormato = new \App\Entity\General\GenFormato();
            $arGenFormato->setCodigoFormatoPk(3);
            $arGenFormato->setNombre('COTIZACION');
            $arGenFormato->setFecha($fecha);
            $arGenFormato->setVersion(1);
            $arGenFormato->setCodigoModeloFk('InvCotizacion');
            $manager->persist($arGenFormato);
        }
        $arGenFormato = $manager->getRepository('App:General\GenFormato')->find(4);
        if(!$arGenFormato){
            $arGenFormato = new \App\Entity\General\GenFormato();
            $arGenFormato->setCodigoFormatoPk(4);
            $arGenFormato->setNombre('CONTRATO');
            $arGenFormato->setFecha($fecha);
            $arGenFormato->setVersion(1);
            $arGenFormato->setCodigoModeloFk('RhuContrato');
            $arGenFormato->setEtiquetas('[
                    {"etiqueta":"#1", "descripcion":"Número de identificación empleado"},
                    {"etiqueta":"#2", "descripcion":"Nombre empleado"},
                    {"etiqueta":"#3", "descripcion":"Dirección empleado"},
                    {"etiqueta":"#4", "descripcion":"Barrio empleado"},
                    {"etiqueta":"#5", "descripcion":"Fecha necimiento empleado"},
                    {"etiqueta":"#6", "descripcion":"Nombre de ciudad de nacimiento empleado"},
                    {"etiqueta":"#7", "descripcion":"Ciudad de recidencia empleado"},
                    {"etiqueta":"#8", "descripcion":"Teléfono empleado"},
                    {"etiqueta":"#9", "descripcion":"Ciudad expidición del documento"},
                    {"etiqueta":"#a", "descripcion":"Ciudad donde labora el empleado"},
                    {"etiqueta":"#b", "descripcion":"Ciudad de celebracion contrato"},
                    {"etiqueta":"#c", "descripcion":"Departamento donde se celebra el contrato"},
                    {"etiqueta":"#d", "descripcion":"Fecha extendida terminacion contrato"},
                    {"etiqueta":"#e", "descripcion":"Fecha desde contrato"},
                    {"etiqueta":"#f", "descripcion":"Fecha hasta contrato"},
                    {"etiqueta":"#g", "descripcion":"Fecha extendida contrato"},
                    {"etiqueta":"#h", "descripcion":"Tiempo laboral"},
                    {"etiqueta":"#i", "descripcion":"Salario en formato numerico"},
                    {"etiqueta":"#j", "descripcion":"Salario en formato de letras"},
                    {"etiqueta":"#k", "descripcion":"Puesto"},
                    {"etiqueta":"#l", "descripcion":"Cargo"},
                    {"etiqueta":"#m", "descripcion":"Entidad censantias"},
                    {"etiqueta":"#n", "descripcion":"Entidad salud"},
                    {"etiqueta":"#o", "descripcion":"Entidad pención"},
                    {"etiqueta":"#p", "descripcion":"Número identificación empresa"},
                    {"etiqueta":"#q", "descripcion":"Digito verificación empresa"},
                    {"etiqueta":"#r", "descripcion":"Nombre empresa"},
                    {"etiqueta":"#s", "descripcion":"Dirección empresa"},
                    {"etiqueta":"#t", "descripcion":"Cliente"},
                    {"etiqueta":"#u", "descripcion":"Nombre de grupo"}
            ]');

            $manager->persist($arGenFormato);
        }
        $manager->flush();
    }
}
