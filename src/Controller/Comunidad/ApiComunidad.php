<?php

namespace App\Controller\Comunidad;
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
        'conexion'                  =>'/api/comunidad/conexion/',
        'buscarAmigos'              =>'/api/comunidad/busqueda/',
        'enviarSolicitud'           =>'/api/comunidad/enviarSolicitud/',
        'cancelarSolicitud'         =>'/api/comunidad/cancelarSolicitud/',
        'eliminarAmigo'             =>'/api/comunidad/eliminarAmigo/',
        'aceptarAmigo'              =>'/api/comunidad/aceptarAmigo/',
        'solicitudes'               =>'/api/comunidad/solicitudesPendientes/',
        'misAmigos'                 =>'/api/comunidad/misAmigos/',
        'misPublicaciones'          =>'/api/comunidad/publicacion/misPublicaciones/',
        'publicaciones'             =>'/api/comunidad/publicacion/publicaciones/',
        'crearPublicacion'          =>'/api/comunidad/publicacion/crear/',
        'crearComentario'           =>'/api/comunidad/publicacion/comentario/crear/',
        'meGustaPublicacion'        =>'/api/comunidad/publicacion/meGusta/',
        'meGustaComentario'         =>'/api/comunidad/comentario/meGusta/',
        'editarPublicacion'         =>'/api/comunidad/publicacion/editar/',
        'editarComentario'          =>'/api/comunidad/comentario/editar/',
        'actualizarPublicacion'     =>'/api/comunidad/publicacion/actualizar/',
        'actualizarComentario'      =>'/api/comunidad/comentario/actualizar/',
        'eliminarComentario'        =>'/api/comunidad/comentario/eliminar/',
        'eliminarPublicacion'       =>'/api/comunidad/publicacion/eliminar/',
    ];

    return $ruta!="todas"?$api[$ruta]:$api;
}

}