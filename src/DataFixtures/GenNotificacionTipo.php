<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenNotificacionTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $genModelo=$manager->getRepository('App:General\GenModelo')->find("GenNotificacionTipo");
        if($genModelo) {
            $usuarios=array("usuario1","usuario2");
            $usuarios=json_encode($usuarios);
            $genNotificacionTipo=$manager->getRepository('App:General\GenNotificacionTipo')->find(1);
            if(!$genNotificacionTipo){
            $genNotificacionTipo = new \App\Entity\General\GenNotificacionTipo();
            $genNotificacionTipo->setCodigoNotificacionTipoPk(1);
            $genNotificacionTipo->setNombre("prueba");
            $genNotificacionTipo->setEstadoActivo(1);
            $genNotificacionTipo->setModeloRel($genModelo);
            $genNotificacionTipo->setUsuarios($usuarios);
            $manager->persist($genNotificacionTipo);
            $manager->flush();
            }
        }
    }
}
