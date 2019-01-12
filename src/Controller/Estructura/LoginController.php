<?php

namespace App\Controller\Estructura;

use App\Entity\General\GenLogConexion;
use App\Utilidades\BaseDatos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function login()
    {
        $em = BaseDatos::getEm();
        $em->getRepository(GenLogConexion::class)->insertar($this->user->getToken(), 'C');
        $em->getRepository(GenLogConexion::class)->actualizarUltimaConexion($this->user->getToken());
        $em->getRepository(GenLogConexion::class)->actualizarConexiones($this->user->getToken());
    }
}