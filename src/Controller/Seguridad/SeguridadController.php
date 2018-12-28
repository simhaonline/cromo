<?php

namespace App\Controller\Seguridad;

use App\Utilidades\Mensajes;
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

        ]);
    }
}

