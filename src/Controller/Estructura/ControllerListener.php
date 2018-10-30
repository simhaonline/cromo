<?php

namespace  App\Controller\Estructura;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener extends Controller {

    private $user;
    private $routeActual;
    /**
     * ControllerListener constructor.
     * @param $user
     */
    public function __construct($user, RequestStack $rs)
    {

        $this->routeActual=$rs->getCurrentRequest()->headers->get('referer');
        $this->user = $user;
    }


    public function getPermissionFunction(FilterControllerEvent $event){
        $url=$this->routeActual;
        $this->routeActual=$event->getRequest()->get('_route');
        $controller = $event->getController();
        $request = $event->getRequest();
        $session = $request->getSession();
        if($controller[0] instanceof ControllerListenerPermisosFunciones){
//            dump($controller);
            $validado=false;
            if($validado){
                $session->set("permiso_denegado",null);
                return;
            }
            else{
                $session->set("permiso_denegado","No tiene permisos para ingresar a la ruta");

                $event->setController(function () use($url){
                    return new RedirectResponse($url);
                });
            }

        }
    }

}