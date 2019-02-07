<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenConfiguracion extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arGenConfiguracion= $manager->getRepository('App:General\GenConfiguracion')->find(1);
        if(!$arGenConfiguracion){
            $arGenConfiguracion = new \App\Entity\General\GenConfiguracion();
            $arGenConfiguracion->setCodigoConfiguracionPk(1);
            $arGenConfiguracion->setNit(1);
            $arGenConfiguracion->setDigitoVerificacion(1);
            $arGenConfiguracion->setNombre('PENDIENTE');
            $arGenConfiguracion->setTelefono(1);
            $arGenConfiguracion->setDireccion('PENDIENTE');
            $arGenConfiguracion->setRutaTemporal('PENDIENTE');
            $arGenConfiguracion->setWebServiceCesioUrl('http://159.65.52.53/cesio/public/index.php');
            $manager->persist($arGenConfiguracion);
        } else {
            $arGenConfiguracion->setVersionBaseDatos(1);
        }
        $manager->flush();
    }
}
