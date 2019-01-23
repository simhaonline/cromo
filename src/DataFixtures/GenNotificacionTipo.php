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
            $manager->persist($genNotificacionTipo);
            $manager->flush();
        }
        $genNotificacionTipo=$manager->getRepository('App:General\GenNotificacionTipo')->find(2);
        if(!$genNotificacionTipo){
            $genNotificacionTipo = new \App\Entity\General\GenNotificacionTipo();
            $genNotificacionTipo->setCodigoNotificacionTipoPk(2);
            $genNotificacionTipo->setNombre("Mercancia vencida en bodega");
            $manager->persist($genNotificacionTipo);
            $manager->flush();
        }

    }
}
