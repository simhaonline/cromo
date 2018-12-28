<?php

namespace App\Controller\Estructura;

use App\Entity\General\GenLogConexion;
use App\Utilidades\BaseDatos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutController implements LogoutHandlerInterface
{
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $em = BaseDatos::getEm();
        $em->getRepository(GenLogConexion::class)->insertar($token,'D');
    }
}