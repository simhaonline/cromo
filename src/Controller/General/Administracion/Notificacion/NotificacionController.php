<?php

namespace App\Controller\General\Administracion\Notificacion;

use App\Controller\MaestroController;
use App\Entity\General\GenNotificacion;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificacionController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "GenNotificacion";

    protected $class = GenNotificacion::class;
    protected $claseNombre = "GenNotificacion";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "Notificacion";
    protected $nombre = "Notificacion";



    /**
     * @Route("/general/administracion/notificacion/notificacion", name="general_administracion_notificacion_notificacion")
     */
    public function notificacionesPendientes(TokenStorageInterface $user)
    {
        $em=$this->getDoctrine()->getManager();
        $usuario=$user->getToken()->getUser();
        $notificacion=$em->getRepository('App:Seguridad\Usuario')->find($usuario->getUsername())->getNotificacionesPendientes();
        $notificaciones=[];
        if($notificacion!=0){
            $notificaciones=$em->getRepository('App:General\GenNotificacion')->notificaciones($usuario->getUsername());
        }
        return new JsonResponse(['notificacionesPendientes'=>$notificacion,'notificaciones'=>$notificaciones]);
    }

    /**
     * @Route("/general/administracion/notificacion/ver", name="general_administracion_notificacion_ver")
     */
    public function verNotificacionesPendientes(TokenStorageInterface $user){
        $em=$this->getDoctrine()->getManager();
        $usuario=$user->getToken()->getUser();
        $arUsuario=$em->getRepository('App:Seguridad\Usuario')->find($usuario->getUsername());
        $arUsuario->setNotificacionesPendientes(0);
        $em->persist($arUsuario);
        $em->flush();

        return new JsonResponse(true);
    }

    /**
     * @param TokenStorageInterface $user
     * @Route("/general/administracion/notificacion/todasNotificaciones", name="general_administracion_notificacion__todasNotificaciones")
     */
    public function todasNotificaciones(Request $request, TokenStorageInterface $user){
        $em=$this->getDoctrine()->getManager();
        $usuario=$user->getToken()->getUser();
        $arNotificacion=$em->getRepository('App:General\GenNotificacion')->lista($usuario->getUsername());
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if($arrSeleccionados){
                    $em->getRepository(GenNotificacion::class)->eliminar($arrSeleccionados);
                }
            }
        }

        $arGenNotificacion= $paginator->paginate($arNotificacion,$request->query->getInt('page',1),500);
        return $this->render('general/administracion/notificacion/notificacion/lista.html.twig',
            ['arGenNotificacion' => $arGenNotificacion,
                'form' => $form->createView()
            ]);
    }

    /**
     *  @Route("/notificaciones", name="notificaciones")
     */
    public function notificaciones(){

        return $this->render('estructura/notificacion.html.twig');
    }
}
