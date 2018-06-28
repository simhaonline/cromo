<?php

namespace App\Controller\Estructura;


use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Mensajes
 * @package App\Util
 */
final class FuncionController
{
    private static function getInstance()
    {
        static $instance = null;
        if($instance === null) {
            $instance = new FuncionController();
        }
        return $instance;
    }

    /**
     * @param $dateFecha \DateTime
     * @param string $intDias
     * @param string $intMeses
     * @param string $intAnios
     * @return \DateTime|false
     */
    public function sumarDiasFecha($dateFecha, $intDias = '', $intMeses = '', $intAnios = '')
    {
        if($dateFecha instanceof \DateTime){
            $fecha = $dateFecha->format('Y-m-j');
        } else {
            $fecha = $dateFecha;
        }
        if($intDias != ''){
            $nuevafecha = strtotime('+' . $intDias . ' day', strtotime($fecha));
        }
        if($intMeses != ''){
            $nuevafecha = strtotime('+' . $intMeses . ' month', strtotime($fecha));
        }
        if($intAnios != ''){
            $nuevafecha = strtotime('+' . $intAnios . ' year', strtotime($fecha));
        }
        $nuevafecha = date('Y-m-j', $nuevafecha);
        $dateNuevaFecha = date_create($nuevafecha);
        return $dateNuevaFecha;
    }

}