<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiExternoController extends FOSRestController
{
    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/externo/guia/crear")
     */
    public function crearGuia(Request $request)
    {

        $em=$this->getDoctrine()->getManager();

        try{
            $datos=json_decode($request->getContent(), true);
            $estado=true;
            $mensaje="exitoso";
            if($datos){
                //validacion de codigo_guia_pk
                if(isset($datos['codigo_guia_pk']) && $datos['codigo_guia_pk']!="" && is_numeric($datos['codigo_guia_pk'])){
                    $arGuiaExistente=$em->getRepository('App\Entity\Transporte\TteGuia')->find($datos['codigo_guia_pk']);
                        $arGuia=new TteGuia();
                    if(!$arGuiaExistente){
                        $arGuia->setCodigoGuiaPk($datos['codigo_guia_pk']);
                    }
                    else{
                        $estado=false;
                        $mensaje="Ya existe una guia con el codigo_guia_pk '{$datos['codigo_guia_pk']}'";
                    }



                    //validacion de codigo_guia_tipo_fk
                    if(isset($datos['codigo_guia_tipo_fk']) && $datos['codigo_guia_tipo_fk']!=""){
                        $arGuiaTipo=$em->getRepository('App\Entity\Transporte\TteGuiaTipo')->find($datos['codigo_guia_tipo_fk']);
                        if($arGuiaTipo){

                            $arGuia->setGuiaTipoRel($arGuiaTipo);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_guia_tipo_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_guia_tipo_fk es obligatorio";
                    }

                    //validacion de codigo_operacion_ingreso_fk
                    if(isset($datos['codigo_operacion_ingreso_fk']) && $datos['codigo_operacion_ingreso_fk']!=""){
                        $arOperacionIngreso=$em->getRepository('App\Entity\Transporte\TteOperacion')->find($datos['codigo_operacion_ingreso_fk']);
                        if($arOperacionIngreso){

                            $arGuia->setOperacionIngresoRel($arOperacionIngreso);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_operacion_ingreso_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_operacion_ingreso_fk es obligatorio";
                    }

                    //validacion de codigo_operacion_cargo_fk
                    if(isset($datos['codigo_operacion_cargo_fk']) && $datos['codigo_operacion_cargo_fk']!=""){
                        $arOperacionCargo=$em->getRepository('App\Entity\Transporte\TteOperacion')->find($datos['codigo_operacion_cargo_fk']);
                        if($arOperacionCargo){

                            $arGuia->setOperacionCargoRel($arOperacionCargo);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_operacion_cargo_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_operacion_cargo_fk es obligatorio";
                    }


                    //validacion de codigo_cliente_fk
                    if(isset($datos['codigo_cliente_fk']) && $datos['codigo_cliente_fk']!=""){
                        $arCliente=$em->getRepository('App\Entity\Transporte\TteCliente')->find($datos['codigo_cliente_fk']);
                        if($arCliente){

                            $arGuia->setClienteRel($arCliente);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_cliente_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_cliente_fk es obligatorio";
                    }


                    //validacion de codigo_ciudad_origen_fk
                    if(isset($datos['codigo_ciudad_origen_fk']) && $datos['codigo_ciudad_origen_fk']!=""){
                        $arCiudadOrigen=$em->getRepository('App\Entity\Transporte\TteCiudad')->find($datos['codigo_ciudad_origen_fk']);
                        if($arCiudadOrigen){

                            $arGuia->setCiudadOrigenRel($arCiudadOrigen);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_ciudad_origen_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_ciudad_origen_fk es obligatorio";
                    }

                    //validacion de codigo_ciudad_destino_fk
                    if(isset($datos['codigo_ciudad_destino_fk']) && $datos['codigo_ciudad_destino_fk']!=""){
                        $arCiudadDestino=$em->getRepository('App\Entity\Transporte\TteCiudad')->find($datos['codigo_ciudad_destino_fk']);
                        if($arCiudadDestino){

                            $arGuia->setCiudadDestinoRel($arCiudadDestino);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_ciudad_destino_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_ciudad_destino_fk es obligatorio";
                    }

                    //validacion de codigo_servicio_fk
                    if(isset($datos['codigo_servicio_fk']) && $datos['codigo_servicio_fk']!=""){
                        $arServicio=$em->getRepository('App\Entity\Transporte\TteServicio')->find($datos['codigo_servicio_fk']);
                        if($arServicio){

                            $arGuia->setServicioRel($arServicio);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_servicio_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_servicio_fk es obligatorio";
                    }


                    //validacion de codigo_producto_fk
                    if(isset($datos['codigo_producto_fk']) && $datos['codigo_producto_fk']!=""){
                        $arProducto=$em->getRepository('App\Entity\Transporte\TteProducto')->find($datos['codigo_producto_fk']);
                        if($arProducto){

                            $arGuia->setProductoRel($arProducto);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_producto_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_producto_fk es obligatorio";
                    }

                    //validacion de codigo_empaque_fk
                    if(isset($datos['codigo_empaque_fk']) && $datos['codigo_empaque_fk']!=""){
                        $arEmpaque=$em->getRepository('App\Entity\Transporte\TteEmpaque')->find($datos['codigo_empaque_fk']);
                        if($arEmpaque){

                            $arGuia->setEmpaqueRel($arEmpaque);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_empaque_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_empaque_fk es obligatorio";
                    }

                    //validacion de codigo_condicion_fk
                    if(isset($datos['codigo_condicion_fk']) && $datos['codigo_condicion_fk']!=""){
                        $arCondicion=$em->getRepository('App\Entity\Transporte\TteCondicion')->find($datos['codigo_condicion_fk']);
                        if($arCondicion){

                            $arGuia->setCondicionRel($arCondicion);
                        }
                        else{
                            $estado=false;
                            $mensaje="El codigo_condicion_fk es inexistente";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El codigo_condicion_fk es obligatorio";
                    }

                    //validacion de numero
                    if(isset($datos['numero']) && $datos['numero']!="" && is_numeric($datos['numero'])){
                        $arGuia->setNumero($datos['numero']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El numero es obligatorio y debe ser numerico";
                    }


                    //validacion de documento_cliente
                    if(isset($datos['documento_cliente']) && $datos['documento_cliente']!="" && is_string($datos['documento_cliente']) && strlen($datos['documento_cliente'])<=80){
                        $arGuia->setDocumentoCliente($datos['documento_cliente']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El documento_cliente es obligatorio, debe ser texto y tamaño maximo de 80 caracteres";
                    }


                    //validacion de relacion_cliente
                    if(isset($datos['relacion_cliente']) && $datos['relacion_cliente']!="" && is_string($datos['relacion_cliente']) && strlen($datos['relacion_cliente'])<=50){
                        $arGuia->setRelacionCliente($datos['relacion_cliente']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El relacion_cliente es obligatorio, debe ser texto y tamaño maximo de 50 caracteres";
                    }

                    //validacion de remitente
                    if(isset($datos['remitente']) && $datos['remitente']!="" && is_string($datos['remitente']) && strlen($datos['remitente'])<=80){
                        $arGuia->setRemitente($datos['remitente']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El remitente es obligatorio, debe ser texto y tamaño maximo de 80 caracteres";
                    }

                    //validacion de nombre_destinatario
                    if(isset($datos['nombre_destinatario']) && $datos['nombre_destinatario']!="" && is_string($datos['nombre_destinatario']) && strlen($datos['nombre_destinatario'])<=150){
                        $arGuia->setRemitente($datos['nombre_destinatario']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El nombre_destinatario es obligatorio, debe ser texto y tamaño maximo de 150 caracteres";
                    }

                    //validacion de direccion_destinatario
                    if(isset($datos['direccion_destinatario']) && $datos['direccion_destinatario']!="" && is_string($datos['direccion_destinatario']) && strlen($datos['direccion_destinatario'])<=150){
                        $arGuia->setDireccionDestinatario($datos['direccion_destinatario']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El direccion_destinatario es obligatorio, debe ser texto y tamaño maximo de 150 caracteres";
                    }

                    //validacion de telefono_destinatario
                    if(isset($datos['telefono_destinatario']) && $datos['telefono_destinatario']!="" && is_string($datos['telefono_destinatario']) && strlen($datos['telefono_destinatario'])<=80){
                        $arGuia->setTelefonoDestinatario($datos['telefono_destinatario']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El telefono_destinatario es obligatorio, debe ser texto y tamaño maximo de 80 caracteres";
                    }

                    //validacion de fecha_ingreso
                    if(isset($datos['fecha_ingreso']) && $datos['fecha_ingreso']!="" && is_string($datos['fecha_ingreso']) && strlen($datos['fecha_ingreso'])<=20){
                        if($this->validarFecha($datos['fecha_ingreso'])){
                            $arGuia->setFechaIngreso(new \DateTime($datos['fecha_ingreso']));
                        }
                        else{
                            $estado=false;
                            $mensaje="Formato de fecha invalido, ingrese un formato valido 'aaaa-mm-dd hh:mm:ss'";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El fecha_ingreso es obligatorio, debe ser texto y tamaño maximo de 20 caracteres";
                    }

                    //validacion de unidades
                    if(isset($datos['unidades']) && $datos['unidades']!="" && is_numeric($datos['unidades'])){
                        $arGuia->setUnidades($datos['unidades']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El unidades es obligatorio y debe ser flotante ";
                    }

                    //validacion de peso_real
                    if(isset($datos['peso_real']) && $datos['peso_real']!="" && is_numeric($datos['peso_real'])){
                        $arGuia->setPesoReal($datos['peso_real']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El peso_real es obligatorio y debe ser flotante ";
                    }

                    //validacion de peso_volumen
                    if(isset($datos['peso_volumen']) && $datos['peso_volumen']!="" && is_numeric($datos['peso_volumen'])){
                        $arGuia->setPesoVolumen($datos['peso_volumen']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El peso_volumen es obligatorio y debe ser flotante ";
                    }


                    //validacion de peso_facturado
                    if(isset($datos['peso_facturado']) && $datos['peso_facturado']!="" && is_numeric($datos['peso_facturado'])){
                        $arGuia->setPesoFacturado($datos['peso_facturado']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El peso_facturado es obligatorio y debe ser flotante ";
                    }

                    //validacion de vr_declara
                    if(isset($datos['vr_declara']) && $datos['vr_declara']!="" && is_numeric($datos['vr_declara'])){
                        $arGuia->setVrDeclara($datos['vr_declara']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El vr_declara es obligatorio y debe ser flotante ";
                    }

                    //validacion de vr_flete
                    if(isset($datos['vr_flete']) && $datos['vr_flete']!="" && is_numeric($datos['vr_flete'])){
                        $arGuia->setVrFlete($datos['vr_flete']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El vr_flete es obligatorio y debe ser flotante ";
                    }

                    //validacion de vr_manejo
                    if(isset($datos['vr_manejo']) && $datos['vr_manejo']!="" && is_numeric($datos['vr_manejo'])){
                        $arGuia->setVrManejo($datos['vr_manejo']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El vr_manejo es obligatorio y debe ser flotante ";
                    }

                    //validacion de vr_recaudo
                    if(isset($datos['vr_recaudo']) && $datos['vr_recaudo']!="" && is_numeric($datos['vr_recaudo'])){
                        $arGuia->setVrRecaudo($datos['vr_recaudo']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El vr_recaudo es obligatorio y debe ser flotante ";
                    }

                    //validacion de mercancia_peligrosa
                    if(isset($datos['mercancia_peligrosa']) && $datos['mercancia_peligrosa']!=""){
                        try{
                            if($datos['mercancia_peligrosa']===false || $datos['mercancia_peligrosa']===true){

                            $arGuia->setMercanciaPeligrosa($datos['mercancia_peligrosa']);
                            }
                            else{
                                $estado=false;
                                $mensaje="El mercancia_peligrosa es obligatorio y debe ser booleano ";
                            }
                        }
                        catch (\Exception $exception){
                            $estado=false;
                            $mensaje="El mercancia_peligrosa es obligatorio y debe ser booleano ";
                        }
                    }
                    else{
                        $estado=false;
                        $mensaje="El mercancia_peligrosa es obligatorio y debe ser booleano ";
                    }


                    //validacion de usuario
                    if(isset($datos['usuario']) && $datos['usuario']!=""  && strlen($datos['usuario'])<=25){
                        $arGuia->setUsuario($datos['usuario']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El usuario es obligatorio, debe ser texto y tamaño maximo de 25";
                    }


                    //validacion de usuario
                    if(isset($datos['comentario']) && $datos['comentario']!=""  && strlen($datos['comentario'])<=1800){
                        $arGuia->setComentario($datos['comentario']);
                    }
                    else{
                        $estado=false;
                        $mensaje="El comentario es obligatorio y tamaño maximo de 1800";
                    }



                    if($estado){
                        $em->persist($arGuia);
                        $em->flush();
                    }

                }
                else{
                    $estado=false;
                    $mensaje="El codigo de la guia es obligatorio y debe ser numerico";
                }
            }
            else{
                $estado=false;
                $mensaje="El formato contiene un error o es invalido";
            }



            return [
                'estado'=>$estado,
                'mensaje'=>$mensaje
            ];

        }catch (\Exception $exception){
            return [
                'estado'=>false,
                'mensaje'=>$exception->getMessage(),
            ];
        }
    }



    function validarFecha($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}