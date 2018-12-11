<?php

namespace App\Controller\Social;

use App\Controller\BaseController;
use App\Entity\Seguridad\Usuario;
use App\Utilidades\Mensajes;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PerfilController extends BaseController
{
    /**
     * @Route("/social/perfil/ver", name="social_perfil_ver")
     * @param $usuario Usuario
     */
    public function verPerfil(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $usuario=$this->container->get('security.token_storage')->getToken()->getUser();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $data=json_encode(['data'=>['estado'=>'']]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            //CURLOPT_URL => 'http://localhost/cromo/public/index.php/documental/api/masivo/masivo/1',
            CURLOPT_URL => $em->getRepository('App:General\GenConfiguracion')->find(1)->getWebServiceCesioUrl() . '/api/social/conexion/' .$usuario->getUsername(),
        ));
        $conexion = json_decode(curl_exec($curl), true);
        curl_close($curl);


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
                return $this->redirect($this->generateUrl('social_perfil_ver'));
            }

            if($form->get('btnBuscar')->isSubmitted()){
                dump("hola");
                exit();
            }
        }
        return $this->render('social/perfil.html.twig',[
            'form'=>$form->createView(),
            'formBusqueda'=>$formBusqueda->createView(),
            'arUsuario'=>$informacionUsuario,
            'conexion'=>$conexion,
        ]);
    }

    /**
     * @Route("/social/perfil/conexion/{username}/{registro}", name="social_perfil_conexion")
     */
    public function conexionUsuario($username, $registro=false){
        $em=$this->getDoctrine()->getManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if($registro){

        $data=json_encode(['data'=>['clave'=>'123456']]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            //CURLOPT_URL => 'http://localhost/cromo/public/index.php/documental/api/masivo/masivo/1',
            CURLOPT_URL =>  $em->getRepository('App:General\GenConfiguracion')->find(1)->getWebServiceCesioUrl() . '/api/social/conexion/' .$username ,
        ));
        }
        else{
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                //CURLOPT_URL => 'http://localhost/cromo/public/index.php/documental/api/masivo/masivo/1',
                CURLOPT_URL =>  $em->getRepository('App:General\GenConfiguracion')->find(1)->getWebServiceCesioUrl() . '/api/social/conexion/' .$username ,
            ));
        }
        curl_exec($curl);
        curl_close($curl);

        return $this->redirect($this->generateUrl('social_perfil_ver'));
    }


}
