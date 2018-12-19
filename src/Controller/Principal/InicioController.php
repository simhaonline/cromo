<?php

namespace App\Controller\Principal;

use App\Entity\General\GenConfiguracion;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="inicio")
     */
    public function inicio(Request $request)
    {
        return $this->render('principal/inicio.html.twig');
    }
}

