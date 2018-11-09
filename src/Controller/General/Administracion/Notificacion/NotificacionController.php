<?php

namespace App\Controller\General\Administracion\Notificacion;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificacionController extends Controller
{
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
        $arGenNotificacion= $paginator->paginate($arNotificacion,$request->query->getInt('page',1),20);
        return $this->render('general/administracion/notificacion/notificacion/lista.html.twig',
            ['arGenNotificacion' => $arGenNotificacion]);
    }
}
