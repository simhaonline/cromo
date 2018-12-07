<?php

namespace App\Controller\Social;

use App\Controller\BaseController;
use App\Entity\Seguridad\Usuario;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $informacionUsuario= [
            'nombreCorto'   =>$usuario->getNombreCorto(),
//            'rol'           =>$usuario->getRoles()[0]=="ROLE_ADMIN"?"Administrador":"Usuario",
//            'cargo'         =>$usuario->getCargo(),
            'correo'        =>$usuario->getEmail(),
            'extension'     =>$usuario->getExtension(),
            'telefono'      =>$usuario->getTelefono(),
        ];
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
        }
        return $this->render('social/perfil.html.twig',[
            'form'=>$form->createView(),
            'arUsuario'=>$informacionUsuario,
        ]);
    }


}
