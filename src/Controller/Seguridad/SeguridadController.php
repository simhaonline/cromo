<?php

namespace App\Controller\Seguridad;

use App\Controller\Comunidad\ApiComunidad;
use App\Entity\General\GenLicencia;
use App\Utilidades\Mensajes;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SeguridadController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        //entity manager
        $em=$this->getDoctrine()->getManager();

        //validar licencia
        $arLicencia=$em->getRepository('App:General\GenLicencia')->findAll();

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('seguridad/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'licencia'      => $arLicencia?$arLicencia[0]->getCodigoLicenciaPk():null

        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        return $this->redirect($this->generateUrl('login'));
    }

    /**
     * @Route("/licencia/activar", name="activar_licencia")
     */
    public function activarLicencia(){
        return $this->render("seguridad/licencia.html.twig",[
            'api'=>ApiComunidad::getApi('todas'),
            'servidor'=>$this->getDoctrine()->getManager()->getRepository('App:General\GenConfiguracion')->find(1)->getWebServiceCesioUrl(),
        ]);
    }


    /**
     * @Route("/licencia/activar/local", name="activar_licencia_local")
     */
    public function activarLicenciaLocal(Request $request)
    {
        $datos = $request->request->get('datos');
        $em=$this->getDoctrine()->getManager();
        $arLicencia=$em->getRepository('App:General\GenLicencia')->findAll();
        if($arLicencia){
            $arLicencia=$arLicencia[0];
            $arLicencia->setCodigoLicenciaPk($datos['clave']);
            $arLicencia->setFechaActivacion(new \DateTime('now'));
            $arLicencia->setFechaValidaHasta(new \DateTime($datos['fechaVencimiento']));
            foreach($datos['modulos'] as $modulo){
                $moduloMayuscula=ucwords($modulo);
                call_user_func(array($arLicencia,"set$moduloMayuscula"),true);
            }
        }
        else{
            $arLicencia=new GenLicencia();
            $arLicencia->setCodigoLicenciaPk($datos['clave']);
            $arLicencia->setFechaActivacion(new \DateTime('now'));
            $arLicencia->setFechaValidaHasta(new \DateTime($datos['fechaVencimiento']));
            $arLicencia->setNumeroUsuarios($datos['numeroUsuarios']);
            foreach($datos['modulos'] as $modulo){
                $moduloMayuscula=ucwords($modulo);
                call_user_func(array($arLicencia,"set$moduloMayuscula"),true);
            }
        }
        $em->persist($arLicencia);
        $em->flush();
        return new JsonResponse("listo");
    }
}

