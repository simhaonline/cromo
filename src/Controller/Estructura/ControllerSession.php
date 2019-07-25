<?php

namespace  App\Controller\Estructura;


use App\Entity\Seguridad\Usuario;
use App\Utilidades\BaseDatos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;


;
class ControllerSession extends Controller
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @param $usuario Usuario
     */
    public function setDatosSession()
    {
        $em=BaseDatos::getEm();
        $session = new Session();
        $usuario=$this->user->getToken()->getUser();
        $arLicencia=$em->createQueryBuilder()
            ->from('App:General\GenLicencia','licencia')
            ->addSelect('licencia.cartera')
            ->addSelect('licencia.compra')
            ->addSelect('licencia.documental')
            ->addSelect('licencia.financiero')
            ->addSelect('licencia.general')
            ->addSelect('licencia.inventario')
            ->addSelect('licencia.recursoHumano')
            ->addSelect('licencia.seguridad')
            ->addSelect('licencia.transporte')
            ->addSelect('licencia.turno')
            ->addSelect('licencia.crm')
            ->addSelect('licencia.tesoreria')
            ->addSelect('licencia.fechaValidaHasta')
            ->addSelect('licencia.codigoLicenciaPk')
            ->getQuery()->getResult();

        if($arLicencia){
            $session->set("licencia",$arLicencia[0]);
        }
        else{
            $session->set("licencia",[]);
        }
        if($usuario->getFoto()){
            $foto="data:image/'jpeg';base64,".base64_encode(stream_get_contents($usuario->getFoto()));
        }
        else{
            $foto="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyBAMAAADsEZWCAAAAElBMVEXR0dGqqqrCwsKysrK5ubnKysqGpJ8RAAAAtUlEQVQ4y72TMQ7CMAxFTaAH+CnsKRI7QepOBvZWgvtfBaESSv1jKRKob32K/W238iOP40lKuATAC9NEvLiy6TBxoSdvwa+2WVCrER+USbMJSxNhNcLMeW3D2UwTzEkHWnXG3FsrtdVg760qdW22nXltSRSNyolmVIPyZ+XJNOo6HGEQo9xeLOP/ZDZmH9t0VmqHiZ5WkNThWKBdKBfxxa1wUP3n34GycmD6vH4m5CbEIc/OyBPDUSvwZuB80QAAAABJRU5ErkJggg==";
        }

        $session->set('foto_perfil', $foto);
    }
}
