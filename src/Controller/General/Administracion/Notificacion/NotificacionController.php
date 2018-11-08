<?php

namespace App\Controller\General\Administracion\Notificacion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Zend\Json\Json;

class NotificacionController extends AbstractController
{
    /**
     * @Route("/general/administracion/notificacion/notificacion", name="general_administracion_notificacion_notificacion")
     */
    public function notificacionesPendientes(TokenStorageInterface $user)
    {
        $em=$this->getDoctrine()->getManager();
        $usuario=$user->getToken()->getUser();
        $notificacion=$em->getRepository('App:Seguridad\Usuario')->find($usuario->getId())->getNotificacionesPendientes();
        $notificaciones=[];
        if($notificacion!=0){
            $notificaciones=$em->getRepository('App:General\GenNotificacion')->notificaciones($usuario->getId());
        }
        return new JsonResponse(['notificacionesPendientes'=>$notificacion,'notificaciones'=>$notificaciones]);
    }

    /**
     * @Route("/general/administracion/notificacion/ver", name="general_administracion_notificacion_ver")
     */
    public function verNotificacionesPendientes(TokenStorageInterface $user){
        $em=$this->getDoctrine()->getManager();
        $usuario=$user->getToken()->getUser();
        $arUsuario=$em->getRepository('App:Seguridad\Usuario')->find($usuario->getId());
        $arUsuario->setNotificacionesPendientes(0);
        $em->persist($arUsuario);
        $em->flush();

        return new JsonResponse(true);
    }
}
