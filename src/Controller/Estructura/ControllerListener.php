<?php

namespace  App\Controller\Estructura;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class ControllerListener{

    private $user;
    private $routeActual;
    /**
     * ControllerListener constructor.
     * @param $user
     */
    public function __construct($user, RequestStack $rs)
    {
//        dump($rs->getCurrentRequest());
//        exit();
        $this->routeActual=$rs->getCurrentRequest()->headers->get('referer');
        $this->user = $user;
    }


    public function getPermissionFunction(FilterControllerEvent $event){
        $this->routeActual=$event->getRequest()->get('_route');
        $controller = $event->getController();
        $request = $event->getRequest();
        $session = $request->getSession();
//        $request2->getBaseUrl();

                $url=$this->routeActual;
        if($controller[0] instanceof ControllerInterface){
            $validado=false;
            if($validado){
                $session->set("permiso_denegado",null);
                return;
            }
            else{
                $session->set("permiso_denegado","No tiene permisos para ingresar a la ruta");
                $event->setController(function() use ($url) {
                    return new RedirectResponse($url);
                });
//                return false;
            }

        }
    }

}