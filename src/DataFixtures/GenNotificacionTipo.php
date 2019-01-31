<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenNotificacionTipo extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $arrTipos = array(
            array('codigo' => 1, 'nombre' => 'Prueba', 'notificacion' => 'Notificacion de prueba', 'modulo' => null),
            array('codigo' => 2, 'nombre' => 'Mercancia vencida en bodega', 'notificacion' => 'Hay existencia de mercancia vencida en bodega', 'modulo' => 'Inventario'),
            array('codigo' => 3, 'nombre' => 'Asesor al aprobar factura', 'notificacion' => 'Se aprobo la factura', 'modulo' => 'Inventario'),
            array('codigo' => 4, 'nombre' => 'Comentario nuevo en modelo', 'notificacion' => 'Se agrego un nuevo comentario', 'modulo' => 'General'),
            array('codigo' => 5, 'nombre' => 'Traslados sin aprobar', 'notificacion' => 'Hay traslados sin aprobar', 'modulo' => 'Inventario'),
            array('codigo' => 6, 'nombre' => 'Remisiones sin aprobar', 'notificacion' => 'Remisiones sin aprobar', 'modulo' => 'Inventario'),
            array('codigo' => 7, 'nombre' => 'Facturas sin aprobar', 'notificacion' => 'Facturas sin aprobar', 'modulo' => 'Inventario'),
            array('codigo' => 8, 'nombre' => 'Pedidos sin aprobar', 'notificacion' => 'Pedidos sin aprobar', 'modulo' => 'Inventario')
        );
        //array('codigo' => , 'nombre' => '', 'notificacion' => '', 'modulo' => '')
        foreach ($arrTipos as $arrTipo) {
            $arNotificacionTipo = $manager->getRepository('App:General\GenNotificacionTipo')->find($arrTipo['codigo']);
            if(!$arNotificacionTipo){
                $arNotificacionTipo = new \App\Entity\General\GenNotificacionTipo();
                $arNotificacionTipo->setCodigoNotificacionTipoPk($arrTipo['codigo']);
                $arNotificacionTipo->setNombre($arrTipo['nombre']);
                $arNotificacionTipo->setNotificacion($arrTipo['notificacion']);
                $arNotificacionTipo->setCodigoModuloFk($arrTipo['modulo']);
                $arNotificacionTipo->setEstadoActivo(0);
                $manager->persist($arNotificacionTipo);
            }
        }
        $manager->flush();
    }
}
