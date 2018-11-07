<?php

namespace  App\Controller\Estructura;

use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener{

    private $user;
    private $routeActual;
    private $em;
    /**
     * ControllerListener constructor.
     * @param $user
     */
    public function __construct($user, RequestStack $rs, EntityManager $em)
    {
        $this->routeActual=$rs->getCurrentRequest()->headers->get('referer');
        $this->user = $user;
        $this->em=$em;
    }


    public function getPermissionFunction(FilterControllerEvent $event){
        $em=$this->em;
        $url=$this->routeActual;
//        if($url==null){
//            $url=
//        }
        $this->routeActual=$event->getRequest()->get('_route');
        $controller = $event->getController();
        $request = $event->getRequest();
        $session = $request->getSession();
        $funcionesProtegidas=array('lista','nuevo','detalle','aprobar','autorizar'.'anular');
        if($controller[0] instanceof ControllerListenerGeneral){
            if(is_array($controller)){
                if(isset($controller[0]) && isset($controller[1])){
                    $arUsuario=$this->user->getToken()->getUser();
                    $arGenModelo=$em->getRepository('App:General\GenModelo')->find($controller[0]->getClaseNombre());
                    if($arGenModelo) {
                        $arSeguridadUsuarioModelo = $em->getRepository('App:Seguridad\SegUsuarioModelo')->findOneBy(['codigoUsuarioFk' => $arUsuario->getId(), 'codigoGenModeloFk' => $arGenModelo->getCodigoModeloPk()]);

                        $permisos = [];
                        if ($arSeguridadUsuarioModelo) {
                            $permisos = array(
                                'lista' => $arSeguridadUsuarioModelo->getLista(),
                                'nuevo' => $arSeguridadUsuarioModelo->getNuevo(),
                                'detalle' => $arSeguridadUsuarioModelo->getDetalle(),
                                'aprobar' => $arSeguridadUsuarioModelo->getAprobar(),
                                'autorizar' => $arSeguridadUsuarioModelo->getAutorizar(),
                                'anular' => $arSeguridadUsuarioModelo->getAnular(),
                            );
                        }
                    }
                    if((isset($permisos[$controller[1]]) && $permisos[$controller[1]]) || !array_key_exists($controller[1],$funcionesProtegidas)){
                        $session->set("permiso_denegado",null);
                        return;
                    }
                    else{
                        $session->set("permiso_denegado","No tiene permisos para ingresar a la ruta");
                        Mensajes::error('No tiene permiso para ingresar a la funcion de '.$controller[1]);
                        $event->setController(function () use($url){
                            return new RedirectResponse($url);
                        });
                    }
                }
            }

        }
    }

}