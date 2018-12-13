<?php

namespace App\Controller\Social;

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
     * @Route("/social/buscar/general/{clave}", name="social_buscar_general")
     */
    public function buscar(Request $request, $clave)
    {
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $arPerfil=FuncionesController::solicitudesGet(ApiSocial::getApi('buscarAmigos').$usuario.'/'.$clave);
        $em=$this->getDoctrine()->getManager();
        $formBusqueda=$this->createFormBuilder()
            ->add('busqueda',TextType::class,
                [
                    'attr'=>['class'=>'form-control'],
                    'required'=>false,
                    'data'=>$clave,
                ])
            ->add('btnBuscar',SubmitType::class,[
                'attr'=>['class'=>'btn btn-default btn-sm'],
                'label'=>'Buscar'
            ])
            ->getForm();
        $formBusqueda->handleRequest($request);

        if($formBusqueda->isSubmitted() && $formBusqueda->isValid()){
            if($formBusqueda->get('btnBuscar')->isSubmitted()){
                if($formBusqueda->get('busqueda')->getData()!=""){

                    return $this->redirect($this->generateUrl('social_buscar_general',['clave'=>$formBusqueda->get('busqueda')->getData()]));
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
        return $this->render('social/buscar/busqueda.html.twig',[
            'perfil'=>$arPerfil['datos'],
            'clave'=>$clave,
            'username'=>$usuario,
            'formBusqueda'=>$formBusqueda->createView(),
        ]);
    }


    /**
     * @Route("/social/buscar/enviarSolicitud/{usernameSolicitado}/{clave}", name="social_enviar_solicitud")
     */
    public function enviarSolicitud($usernameSolicitado, $clave){
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $enviarSolicitud=FuncionesController::solicitudesGet(ApiSocial::getApi('enviarSolicitud').$usuario.'/'.$usernameSolicitado);
        if($enviarSolicitud['estado']){
            return $this->redirect($this->generateUrl('social_buscar_general',['clave'=>$clave]));
        }
    }

    /**
     * @Route("/social/buscar/eliminarAmigo/{usernameSolicitado}/{clave}", name="social_eliminar_amigo")
     */
    public function eliminarAmigo($usernameSolicitado, $clave){
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $eliminarAmigo=FuncionesController::solicitudesGet(ApiSocial::getApi('eliminarAmigo').$usuario.'/'.$usernameSolicitado);
        if($eliminarAmigo['estado']){
            return $this->redirect($this->generateUrl('social_buscar_general',['clave'=>$clave]));
        }
    }


    /**
     * @Route("/social/buscar/agregarAmigo/{usernameSolicitado}/{clave}", name="social_agregar_amigo")
     */
    public function agregarAmigo($usernameSolicitado, $clave){
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $agregarAmigo=FuncionesController::solicitudesGet(ApiSocial::getApi('agregarAmigo').$usuario.'/'.$usernameSolicitado);
        if($agregarAmigo['estado']){
            return $this->redirect($this->generateUrl('social_buscar_general',['clave'=>$clave]));
        }
    }

    /**
     * @Route("/social/buscar/cancelarSolicitud/{usernameSolicitado}/{clave}", name="social_cancelar_solicitud")
     */
    public function cancelarSolicitud($usernameSolicitado, $clave){
        $usuario=$this->get('security.token_storage')->getToken()->getUsername();
        $cancelarSolicitud=FuncionesController::solicitudesGet(ApiSocial::getApi('cancelarSolicitud').$usuario.'/'.$usernameSolicitado);
        if($cancelarSolicitud['estado']){
            return $this->redirect($this->generateUrl('social_buscar_general',['clave'=>$clave]));
        }
    }
}
