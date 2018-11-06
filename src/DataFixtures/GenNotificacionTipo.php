<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenNotificacionTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $genModelo=$manager->getRepository('App:General\GenModelo')->find("FinAsiento");
        if($genModelo) {
            $usuarios=array("usuario1","usuario2");
            $usuarios=json_encode($usuarios);
            $genNotificacionTipo = new \App\Entity\General\GenNotificacionTipo();
            $genNotificacionTipo->setNombre("prueba");
            $genNotificacionTipo->setEstadoActivo(1);
            $genNotificacionTipo->setModeloRel($genModelo);
            $genNotificacionTipo->setUsuarios($usuarios);
            $manager->persist($genNotificacionTipo);
            $manager->flush();
        }
    }
}
