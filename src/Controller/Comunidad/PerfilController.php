<?php

namespace App\Controller\Comunidad;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Seguridad\Usuario;
use App\Utilidades\Mensajes;
use FOS\RestBundle\Controller\Annotations as Rest;
use http\Env\Response;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PerfilController extends BaseController
{
    /**
     * @Route("/comunidad/perfil/ver", name="comunidad_perfil_ver")
     * @param $usuario Usuario
     */
    public function verPerfil(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $usuario=$this->container->get('security.token_storage')->getToken()->getUser();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $datos=json_encode(['datos'=>['estado'=>'']]);
        $dato=json_encode(['datos'=>['maximo_resultado'=>10]]);
        $conexion= FuncionesController::solicitudesPost($datos,ApiComunidad::getApi('conexion') .$usuario->getUsername());
        $amigos= FuncionesController::solicitudesPost($dato,ApiComunidad::getApi('misAmigos') .$usuario->getUsername());
        $publicaciones=(new PublicacionController())->misPublicaciones($usuario->getUsername());
        $informacionUsuario= [
            'nombreCorto'   =>$usuario->getNombreCorto(),
//            'rol'           =>$usuario->getRoles()[0]=="ROLE_ADMIN"?"Administrador":"Usuario",
//            'cargo'         =>$usuario->getCargo(),r4e
            'correo'        =>$usuario->getEmail(),
            'extension'     =>$usuario->getExtension(),
            'telefono'      =>$usuario->getTelefono(),
        ];

        $formBusqueda=$this->createFormBuilder()
            ->add('busqueda',TextType::class,
                [
                    'attr'=>['class'=>'form-control'],
                    'required'=>false
                ])
            ->add('btnBuscar',SubmitType::class,[
                'attr'=>['class'=>'btn btn-default btn-sm'],
                'label'=>'Buscar'
            ])
            ->getForm();
        $formBusqueda->handleRequest($request);
        $misSolicitudes=(new BuscarController())->misSolicitudesPendientes($usuario->getUsername());
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

        $form= $this->createFormBuilder()
            ->add('foto_perfil_subir',FileType::class,[
                'required'=>true,
                'attr'=>['style'=>'display:none','accept'=>'image/*', 'name'=>'files[]'],
            ])
            ->add('btnSubirFoto',SubmitType::class,[
                'attr'=>['class'=>'btn btn-primary'],
                'label'=>'Subir foto'
            ])
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('btnSubirFoto')->isSubmitted()){
                $arUsuario=$em->getRepository('App:Seguridad\Usuario')->find($usuario->getUsername());
                $imagen = fopen($form->get('foto_perfil_subir')->getData()->getRealPath(),'rb');
                $arUsuario->setFoto(stream_get_contents($imagen));
                $em->persist($arUsuario);
                $em->flush();
                $imagen=base64_encode($arUsuario->getFoto());
                $session=new Session();
                $session->set('foto_perfil',"data:image/jpeg;base64,{$imagen}");
                return $this->redirect($this->generateUrl('comunidad_perfil_ver'));
            }

        }
        $unknown->getVoid();
//        $response = new \Symfony\Component\HttpFoundation\Response();
//        $response->setStatusCode(500);
//        return $response;

        return $this->render('comunidad/perfil.html.twig',[
            'form'=>$form->createView(),
            'formBusqueda'=>$formBusqueda->createView(),
            'arUsuario'=>$informacionUsuario,
            'conexion'=>$conexion,
            'misSolicitudes'=>$misSolicitudes['datos'],
            'amigos'=>$amigos['datos'],
            'publicaciones'=>$publicaciones['datos']
        ]);
    }

    /**
     * @Route("/comunidad/perfil/conexion/{username}/{registro}", name="comunidad_perfil_conexion")
     */
    public function conexionUsuario($username, $registro=false){
        $em=$this->getDoctrine()->getManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if($registro=='true'){
        $nombreCorto=$em->getRepository('App:Seguridad\Usuario')->find($username)->getNombreCorto();
        $datos=json_encode(['datos'=>['clave'=>'123456','nombreCorto'=>$nombreCorto]]);
            FuncionesController::solicitudesPost($datos,ApiComunidad::getApi('conexion').$username);
        }
        else{
            FuncionesController::solicitudesPost([],ApiComunidad::getApi('conexion').$username);
        }


        return $this->redirect($this->generateUrl('comunidad_perfil_ver'));
    }

    /**
     * @Route("/comunidad/perfil/verAmigos/{$username}", name="comunidad_perfil_verAmigos")
     */
    public function verAmigos(){

    }


}
