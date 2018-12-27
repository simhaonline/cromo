<?php

namespace App\Controller\Comunidad;

use App\Controller\Estructura\FuncionesController;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    /**
     * @Route("/comunidad/general/inicio", name="comunidad_general_inicio")
     */
    public function index(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $dominio=$this->getDoctrine()->getRepository('App:General\GenConfiguracion')->find(1)->getDominio();

        $dominio="@".$dominio??"";
        $usuario=$this->container->get('security.token_storage')->getToken()->getUser();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $datos=json_encode(['datos'=>['estado'=>'']]);
        $dato=json_encode(['datos'=>['maximo_resultado'=>20]]);
        $conexion= FuncionesController::solicitudesPost($datos,ApiComunidad::getApi('conexion') .$usuario->getUsername().$dominio);
        $amigos= FuncionesController::solicitudesPost($dato,ApiComunidad::getApi('misAmigos') .$usuario->getUsername().$dominio);
        $publicaciones=FuncionesController::solicitudesGet(ApiComunidad::getApi('publicaciones') .$usuario->getUsername().$dominio);
        $informacionUsuario= [
            'nombreCorto'   =>$usuario->getNombreCorto(),
//            'rol'           =>$usuario->getRoles()[0]=="ROLE_ADMIN"?"Administrador":"Usuario",
            'cargo'         =>$usuario->getCargo()??"Cargo no especificado",
            'correo'        =>$usuario->getEmail(),
            'extension'     =>$usuario->getExtension(),
            'telefono'      =>$usuario->getTelefono(),
        ];

        $formBusqueda=$this->createFormBuilder()
            ->add('busqueda',TextType::class,
                [
                    'attr'=>['class'=>'form-control'],
                    'required'=>false,
                    'disabled'=>$conexion['usuario'] && $conexion['usuario']!==""?false:true

                ])
            ->add('btnBuscar',SubmitType::class,[
                'attr'=>['class'=>'btn btn-default btn-sm'],
                'label'=>'Buscar',
                'disabled'=>$conexion['usuario'] && $conexion['usuario']!==""?false:true
            ])
            ->getForm();
        $formBusqueda->handleRequest($request);
        $misSolicitudes=(new BuscarController())->misSolicitudesPendientes($usuario->getUsername().$dominio);
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


//        $unknown->getVoid();


        return $this->render('comunidad/inicio.html.twig',[
            'formBusqueda'=>$formBusqueda->createView(),
            'arUsuario'=>$informacionUsuario,
            'conexion'=>$conexion,
            'misSolicitudes'=>$misSolicitudes['datos'],
            'amigos'=>$amigos['datos'],
            'numerosAmigos'=>$amigos['numeroAmigos'],
            'publicaciones'=>$publicaciones['datos'],
            'dominio'=>$dominio,
            'servidor'=>$em->getRepository('App:General\GenConfiguracion')->find(1)->getWebServiceCesioUrl(),
            'api'=>ApiComunidad::getApi('todas'),
            'username'=>$usuario->getUsername().$dominio
        ]);

    }
}
