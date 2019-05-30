<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class TurPedidoTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arPedidoTipo = $manager->getRepository(\App\Entity\Turno\TurPedidoTipo::class)->find('CON');
        if(!$arPedidoTipo){
            $arPedidoTipo = new \App\Entity\Turno\TurPedidoTipo();
            $arPedidoTipo->setCodigoPedidoTipoPk('CON');
            $arPedidoTipo->setNombre('CONTRATO');
            $manager->persist($arPedidoTipo);
        }
        $manager->flush();
    }
}
