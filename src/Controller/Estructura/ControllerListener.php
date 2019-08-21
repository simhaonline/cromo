<?php

namespace  App\Controller\Estructura;

use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener extends Controller{

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
        if(!$this->routeActual){
            $this->routeActual="{$rs->getCurrentRequest()->getScheme()}://{$rs->getCurrentRequest()->getHost()}{$rs->getCurrentRequest()->getBaseUrl()}";

        }
        $this->user = $user;
        $this->em=$em;
    }


    public function getPermissionFunction(FilterControllerEvent $event){
        $em=$this->em;
        $url=$this->routeActual;
        $this->routeActual=$event->getRequest()->get('_route');
        $controller = $event->getController();
        if($controller[0] instanceof ControllerListenerGeneral){
            if(is_array($controller)){
                if(isset($controller[0]) && isset($controller[1])){
                    if($controller[0]->getProceso()){
                        $this->getPermisosProcesos($em, $controller, $event, $url);
                    }
                    else{
                        $this->getPermisoModelo($em,$controller, $event, $url);
                    }
                }
            }

        }
    }

    /**
     * @param $em EntityManager
     * @param $controller
     * @param $event
     * @param $url
     */
    public function getPermisoModelo($em,$controller, $event, $url){
        $requestPhp=$_REQUEST;
        $funcionesProtegidas=array('lista','nuevo','detalle','aprobar','autorizar','anular');
        $arUsuarioRol=$this->user->getToken()->getRoles()[0]->getRole()??"ROLE_USER";
        $arUsuario=$this->user->getToken()->getUser();
        $arGenModelo=$em->getRepository('App:General\GenModelo')->find($controller[0]->getClaseNombre());
        if($arGenModelo) {
            $arSeguridadUsuarioModelo = $em->getRepository('App:Seguridad\SegUsuarioModelo')->findOneBy(['codigoUsuarioFk' => $arUsuario->getUsername(), 'codigoModeloFk' => $arGenModelo->getCodigoModeloPk()]);

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
        if((isset($permisos[$controller[1]]) && $permisos[$controller[1]]) || !in_array($controller[1],$funcionesProtegidas) || $arUsuarioRol=="ROLE_ADMIN"){
            if($controller[1]==="detalle"){
                foreach ($permisos as $key=>$permiso){
                    $permisoMayuscula=ucfirst($key);
                    if(isset($requestPhp['form']['btn'.$permisoMayuscula])){
                        if(call_user_func(array($arSeguridadUsuarioModelo,"get{$permisoMayuscula}")) || $arUsuarioRol=="ROLE_ADMIN"){
                            return;
                        }
                        else{
                            $this->redireccionar($event, $url, "No tiene permisos para esta funcionalidad '{$permisoMayuscula}'");
                        }
                    }
                }
            }
            return;
        }
        else{
            $this->redireccionar($event, $url, "No tiene permisos para esta funcionalidad '{$controller[1]}'");
        }
    }

    /**
     * @param $em EntityManager
     * @param $controller
     * @param $event
     * @param $url
     */
    public function getPermisosProcesos($em, $controller, $event, $url){
        $codigoProceso = $controller[0]->getProceso();
        $codigoUsuario = $this->user->getToken()->getUserName();
        $arUsuarioRol=$this->user->getToken()->getRoles()[0]->getRole()??"ROLE_USER";
        $arSegUsuarioProceso=$em->getRepository('App:Seguridad\SegUsuarioProceso')->findOneBy(
            [
                'codigoProcesoFk'=> $codigoProceso,
                'codigoUsuarioFk' => $codigoUsuario
            ]);
        $arProceso=$em->getRepository('App:General\GenProceso')->find($controller[0]->getProceso());
        if($arSegUsuarioProceso && $arUsuarioRol!="ROLE_ADMIN"){
            if(!$arSegUsuarioProceso->getIngreso()){
                $this->redireccionar($event, $url, "No tiene permiso para ingresar al proceso '{$arProceso->getNombre()}'");
        }
            return;

        }
        elseif($arUsuarioRol=="ROLE_ADMIN"){
            return;
        }
        else{
            $this->redireccionar($event, $url, "No tiene permiso para ingresar al proceso '{$arProceso->getNombre()}'");
        }
    }

    public function redireccionar($event, $url, $mensage){
        Mensajes::error($mensage);
        $event->setController(function () use($url) {
            return new RedirectResponse($url);
        });
    }
}
