<?php

namespace App\Controller\Comunidad;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;


class BuscarController extends BaseController
{
    /**
     * @Route("/comunidad/buscar/general/{clave}", name="comunidad_buscar_general")
     */
    public function buscar(Request $request, $clave)
    {
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $dominio=$this->getDoctrine()->getRepository('App:General\GenConfiguracion')->find(1)->getDominio();
        $dominio="@".$dominio??"";
        $arPerfil=FuncionesController::solicitudesGet(ApiComunidad::getApi('buscarAmigos').$usuario.$dominio.'/'.$clave);
        $em=$this->getDoctrine()->getManager();
        $misSolicitudes=BuscarController::misSolicitudesPendientes($usuario.$dominio);
        $formBusqueda=$this->createFormBuilder()
            ->add('busqueda',TextType::class,
                [
                    'attr'=>['class'=>'form-control'],
                    'required'=>false,
                    'allow_extra_fields'=>true,
                    'data'=>$clave,
                ])
            ->add('btnBuscar',SubmitType::class,[
                'attr'=>['class'=>'btn btn-default btn-sm'],
                'validation_groups'=>false,
                'label'=>'Buscar'
            ])
            ->getForm();
        $formBusqueda->handleRequest($request);

        if($formBusqueda->isSubmitted() && $formBusqueda->isValid()){
            if($formBusqueda->get('btnBuscar')->isSubmitted()){
                if($formBusqueda->get('busqueda')->getData()!=""){

                    return $this->redirect($this->generateUrl('comunidad_buscar_general',['clave'=>$formBusqueda->get('busqueda')->getData()]));
                }
                else{
                    Mensajes::error("Debes ingresar una palabra o frase para iniciar la busqueda");

                }
            }
        }
//        $arPerfil=$em->getRepository('App:Social\SocPerfil')->listaPerfil($clave, $usuario);
//        for($i=0;$i<count($arPerfil);$i++){
//            $arPerfil[$i]['foto']="data:image/'jpeg';base64,".base64_encode(stream_get_contents($arPerfil[$i]['foto']));
//        }
        return $this->render('comunidad/buscar/busqueda.html.twig',[
            'perfil'=>$arPerfil['datos'],
            'clave'=>$clave,
            'username'=>$usuario,
            'formBusqueda'=>$formBusqueda->createView(),
            'misSolicitudes'=>$misSolicitudes['datos'],
        ]);
    }


    /**
     * @Route("/comunidad/buscar/enviarSolicitud/{usernameSolicitado}/{clave}", name="comunidad_enviar_solicitud")
     */
    public function enviarSolicitud($usernameSolicitado, $clave){
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $dominio=$this->getDoctrine()->getRepository('App:General\GenConfiguracion')->find(1)->getDominio();
        $dominio="@".$dominio??"";
        $enviarSolicitud=FuncionesController::solicitudesGet(ApiComunidad::getApi('enviarSolicitud').$usuario.$dominio.'/'.$usernameSolicitado);
        if($enviarSolicitud['estado']){
            return $this->redirect($this->generateUrl('comunidad_buscar_general',['clave'=>$clave]));
        }
    }

    /**
     * @Route("/comunidad/buscar/eliminarAmigo/{usernameSolicitado}/{clave}", name="comunidad_eliminar_amigo")
     */
    public function eliminarAmigo($usernameSolicitado, $clave){
        $dominio=$this->getDoctrine()->getRepository('App:General\GenConfiguracion')->find(1)->getDominio();
        $dominio="@".$dominio??"";
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $eliminarAmigo=FuncionesController::solicitudesGet(ApiComunidad::getApi('eliminarAmigo').$usuario.$dominio.'/'.$usernameSolicitado);
        if($eliminarAmigo['estado']){
            return $this->redirect($this->generateUrl('comunidad_buscar_general',['clave'=>$clave]));
        }
    }


    /**
     * @Route("/comunidad/buscar/agregarAmigo/{usernameSolicitado}/{clave}/{notificacion}", name="comunidad_agregar_amigo")
     */
    public function agregarAmigo($usernameSolicitado, $clave, $notificacion=false){
        $dominio=$this->getDoctrine()->getRepository('App:General\GenConfiguracion')->find(1)->getDominio();
        $dominio="@".$dominio??"";
        $notificacion=(boolean)$notificacion;
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $agregarAmigo=FuncionesController::solicitudesGet(ApiComunidad::getApi('aceptarAmigo').$usuario.$dominio.'/'.$usernameSolicitado);
        if($agregarAmigo['estado'] && !$notificacion){
            return $this->redirect($this->generateUrl('comunidad_buscar_general',['clave'=>$clave]));
        }
        else{
            return $this->redirect($this->generateUrl('comunidad_perfil_ver'));
        }
    }

    /**
     * @Route("/comunidad/buscar/cancelarSolicitud/{usernameSolicitado}/{clave}", name="comunidad_cancelar_solicitud")
     */
    public function cancelarSolicitud($usernameSolicitado, $clave){
        $dominio=$this->getDoctrine()->getRepository('App:General\GenConfiguracion')->find(1)->getDominio();
        $dominio="@".$dominio??"";
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $cancelarSolicitud=FuncionesController::solicitudesGet(ApiComunidad::getApi('cancelarSolicitud').$usuario.$dominio.'/'.$usernameSolicitado);
        if($cancelarSolicitud['estado']){
            return $this->redirect($this->generateUrl('comunidad_buscar_general',['clave'=>$clave]));
        }
    }


    public function misSolicitudesPendientes($username){

        $solicitudes=FuncionesController::solicitudesGet(ApiComunidad::getApi('solicitudes').$username);
        if($solicitudes['estado']){
            return $solicitudes;
        }
    }
}
