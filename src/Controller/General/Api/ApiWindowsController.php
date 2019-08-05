<?php

namespace App\Controller\General\Api;

use App\Entity\General\GenCiudad;
use App\Entity\General\GenIdentificacion;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiWindowsController extends FOSRestController
{

    /**
     * @return array
     * @Rest\Get("/general/api/windows/identificacion/lista")
     */
    public function identificacionLista(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(GenIdentificacion::class)->apiWindowsLista($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Get("/general/api/windows/ciudad/lista")
     */
    public function ciudadLista(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(GenCiudad::class)->apiWindowsLista($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

}
