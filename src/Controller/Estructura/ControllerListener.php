<?php

namespace  App\Controller\Estructura;

use App\Controller\MaestroController;
use App\Entity\General\GenModelo;
use App\Entity\General\GenProceso;
use App\Entity\Seguridad\SegGrupoModelo;
use App\Entity\Seguridad\SegUsuarioModelo;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener extends MaestroController{

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
        $controlador = $event->getController();
        if($controlador[0] instanceof MaestroController){
            if(isset($controlador[0]) && isset($controlador[1])){
                $controladorInformacion = $controlador[0];
                $tipo = $controladorInformacion->tipo;
                $metodo = $controlador[1];
                if($tipo == 'movimiento') {
                    $modelo = $controladorInformacion->modelo;
                    $this->getPermisoModelo($em, $modelo, $metodo, $event, $url);
                }
                if($controladorInformacion->tipo == 'proceso') {
                    $proceso = $controladorInformacion->proceso;
                    $this->getPermisoProceso($em, $proceso, $metodo, $event, $url);
                }
            }
        }

        /*if($controller[0] instanceof ControllerListenerGeneral){
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
        }*/
    }

    /**
     * @param $em EntityManager
     * @param $controller
     * @param $event
     * @param $url
     */
    public function getPermisoModelo($em, $modelo, $metodo, $event, $url){
        $requestPhp = $_REQUEST;
        $funcionesProtegidas = array('lista','nuevo','detalle','aprobar','autorizar','anular');
        $arUsuarioRol=$this->user->getToken()->getRoles()[0]->getRole()??"ROLE_USER";
        $arUsuario=$this->user->getToken()->getUser();
        $arModelo = $em->getRepository(GenModelo::class)->find($modelo);

        if($arModelo) {
            if($arUsuarioRol=="ROLE_ADMIN") {
                return;
            } else {
                $permisoGrupo = false;
                if($arUsuario->getCodigoGrupoFk()) {
                    $arGrupoModelo = $em->getRepository(SegGrupoModelo::class)->findOneBy(['codigoGrupoFk' => $arUsuario->getCodigoGrupoFk(), 'codigoModeloFk' => $modelo]);
                    if($arGrupoModelo) {
                        switch ($metodo) {
                            case "lista":
                                if($arGrupoModelo->getLista()) {
                                    $permisoGrupo = true;
                                }
                                break;
                            case "nuevo":
                                if($arGrupoModelo->getNuevo()) {
                                    $permisoGrupo = true;
                                }
                                break;
                            case "detalle":
                                if($arGrupoModelo->getDetalle()) {
                                    $permisoGrupo = true;
                                }
                                break;
                            default:
                                $permisoGrupo = true;
                                break;
                        }
                    }
                }
                if($permisoGrupo == true) {
                    return;
                }
                $permisoUsuario = false;
                $arUsuarioModelo = $em->getRepository(SegUsuarioModelo::class)->findOneBy(['codigoUsuarioFk' => $arUsuario->getUsername(), 'codigoModeloFk' => $modelo]);
                if($arUsuarioModelo) {
                    switch ($metodo) {
                        case "lista":
                            if($arUsuarioModelo->getLista()) {
                                $permisoUsuario = true;
                            }
                            break;
                        case "nuevo":
                            if($arUsuarioModelo->getNuevo()) {
                                $permisoUsuario = true;
                            }
                            break;
                        case "detalle":
                            if($arUsuarioModelo->getDetalle()) {
                                $permisoUsuario = true;
                            }
                            break;
                        default:
                            $permisoUsuario = true;
                            break;
                    }


                }
                if($permisoUsuario) {
                    return;
                } else {
                    $this->redireccionar($event, $url, "No tiene permisos asignados para el modulo " . $arModelo->getCodigoModuloFk() . " funcion " . $arModelo->getCodigoFuncionFk() . " grupo " . $arModelo->getCodigoGrupoFk() . " opcion " . $arModelo->getCodigoModeloPk());
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
    public function getPermisoProceso($em, $proceso, $metodo, $event, $url){
        $arUsuarioRol=$this->user->getToken()->getRoles()[0]->getRole()??"ROLE_USER";
        $arUsuario=$this->user->getToken()->getUser();
        $arProceso = $em->getRepository(GenProceso::class)->find($proceso);

        if($arProceso) {
            if($arUsuarioRol=="ROLE_ADMIN") {
                return;
            } else {
                $permisoGrupo = false;
                if($arUsuario->getCodigoGrupoFk()) {
                    $arGrupoModelo = $em->getRepository(SegGrupoModelo::class)->findOneBy(['codigoGrupoFk' => $arUsuario->getCodigoGrupoFk(), 'codigoModeloFk' => $modelo]);
                    if($arGrupoModelo) {
                        switch ($metodo) {
                            case "lista":
                                if($arGrupoModelo->getLista()) {
                                    $permisoGrupo = true;
                                }
                                break;
                            case "nuevo":
                                if($arGrupoModelo->getNuevo()) {
                                    $permisoGrupo = true;
                                }
                                break;
                            case "detalle":
                                if($arGrupoModelo->getDetalle()) {
                                    $permisoGrupo = true;
                                }
                                break;
                            default:
                                $permisoGrupo = true;
                                break;
                        }
                    }
                }
                if($permisoGrupo == true) {
                    return;
                }
                $permisoUsuario = false;
                $arUsuarioModelo = $em->getRepository(SegUsuarioModelo::class)->findOneBy(['codigoUsuarioFk' => $arUsuario->getUsername(), 'codigoModeloFk' => $modelo]);
                if($arUsuarioModelo) {
                    switch ($metodo) {
                        case "lista":
                            if($arUsuarioModelo->getLista()) {
                                $permisoUsuario = true;
                            }
                            break;
                        case "nuevo":
                            if($arUsuarioModelo->getNuevo()) {
                                $permisoUsuario = true;
                            }
                            break;
                        case "detalle":
                            if($arUsuarioModelo->getDetalle()) {
                                $permisoUsuario = true;
                            }
                            break;
                        default:
                            $permisoUsuario = true;
                            break;
                    }


                }
                if($permisoUsuario) {
                    return;
                } else {
                    $this->redireccionar($event, $url, "No tiene permisos asignados para el modulo " . $arModelo->getCodigoModuloFk() . " funcion " . $arModelo->getCodigoFuncionFk() . " grupo " . $arModelo->getCodigoGrupoFk() . " opcion " . $arModelo->getCodigoModeloPk());
                }
            }
        }
    }

    public function redireccionar($event, $url, $mensage){
        Mensajes::error($mensage);
        $event->setController(function () use($url) {
            return new RedirectResponse($url);
        });
    }
}
