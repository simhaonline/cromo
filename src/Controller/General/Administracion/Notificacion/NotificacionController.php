<?php

namespace App\Controller\General\Administracion\Notificacion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Json\Json;

class NotificacionController extends AbstractController
{
    /**
     * @Route("/general/administracion/notificacion/notificacion", name="general_administracion_notificacion_notificacion")
     */
    public function index()
    {
        return new JsonResponse(true);
    }
}
