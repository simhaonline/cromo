<?php

namespace App\Controller\Social;
final class ApiSocial
{

private static function getInstance()
{
static $instance = null;
if ($instance === null) {
$instance = new ApiSocial();
}
return $instance;
}

public static function getApi($ruta){
    $api=[
        'conexion'          =>'/api/social/conexion/',
        'buscarAmigos'      =>'/api/social/busqueda/',
        'enviarSolicitud'   =>'/api/social/enviarSolicitud/',
        'cancelarSolicitud' =>'/api/social/cancelarSolicitud/',
        'eliminarAmigo'     =>'/api/social/eliminarAmigo/',
        'aceptarAmigo'      =>'/api/social/aceptarAmigo/',
        'solicitudes'       =>'/api/social/solicitudesPendientes/',
        'misAmigos'         =>'/api/social/misAmigos/'
    ];

    return $api[$ruta];
}

}