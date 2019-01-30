<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenNotificacionTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $genNotificacionTipo=$manager->getRepository('App:General\GenNotificacionTipo')->find(1);
        if(!$genNotificacionTipo){
            $genNotificacionTipo = new \App\Entity\General\GenNotificacionTipo();
            $genNotificacionTipo->setCodigoNotificacionTipoPk(1);
            $genNotificacionTipo->setNombre("Prueba");
            $genNotificacionTipo->setNotificacion("Notificacion de prueba");
            $manager->persist($genNotificacionTipo);
            $manager->flush();
        }
        $genNotificacionTipo=$manager->getRepository('App:General\GenNotificacionTipo')->find(2);
        if(!$genNotificacionTipo){
            $genNotificacionTipo = new \App\Entity\General\GenNotificacionTipo();
            $genNotificacionTipo->setCodigoNotificacionTipoPk(2);
            $genNotificacionTipo->setNombre("Mercancia vencida en bodega");
            $genNotificacionTipo->setNotificacion("Hay existencia de mercancia vencida en bodega");
            $genNotificacionTipo->setCodigoModuloFk("Inventario");
            $genNotificacionTipo->setEstadoActivo(0);
            $manager->persist($genNotificacionTipo);
            $manager->flush();
        }
        $genNotificacionTipo=$manager->getRepository('App:General\GenNotificacionTipo')->find(3);
        if(!$genNotificacionTipo){
            $genNotificacionTipo = new \App\Entity\General\GenNotificacionTipo();
            $genNotificacionTipo->setCodigoNotificacionTipoPk(3);
            $genNotificacionTipo->setNombre("Asesor al aprobar factura");
            $genNotificacionTipo->setNotificacion("Se aprobo la factura");
            $genNotificacionTipo->setCodigoModuloFk("Inventario");
            $genNotificacionTipo->setEstadoActivo(0);
            $manager->persist($genNotificacionTipo);
            $manager->flush();
        }

    }
}
