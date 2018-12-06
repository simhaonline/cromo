<?php

namespace App\Controller\Social;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PerfilController extends AbstractController
{
    /**
     * @Route("/social/perfil/ver", name="social_perfil_ver")
     */
    public function ver()
    {
        return $this->render('social/perfil.html.twig');
    }
}
