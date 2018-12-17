<?php

namespace App\Controller\Social;
final class ApiComunidad
{

private static function getInstance()
{
static $instance = null;
if ($instance === null) {
$instance = new ApiComunidad();
}
return $instance;
}

public static function getApi($ruta){
    $api=[
        'conexion'          =>'/api/comunidad/conexion/',
        'buscarAmigos'      =>'/api/comunidad/busqueda/',
        'enviarSolicitud'   =>'/api/comunidad/enviarSolicitud/',
        'cancelarSolicitud' =>'/api/comunidad/cancelarSolicitud/',
        'eliminarAmigo'     =>'/api/comunidad/eliminarAmigo/',
        'aceptarAmigo'      =>'/api/comunidad/aceptarAmigo/',
        'solicitudes'       =>'/api/comunidad/solicitudesPendientes/',
        'misAmigos'         =>'/api/comunidad/misAmigos/'
    ];

    return $api[$ruta];
}

}