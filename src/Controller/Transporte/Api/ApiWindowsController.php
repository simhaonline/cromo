<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteNovedadTipo;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiWindowsController extends FOSRestController
{

    /**
     * @return array
     * @Rest\Post("/api/windows/usuario/validar")
     */
    public function lista(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(Publicacion::class)->lista($raw);
        } catch (\Exception $e) {
            return [
                'error' => true,
            ];
        }
    }


}
