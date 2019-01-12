<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiExternoController extends FOSRestController
{
    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/externo/guia/crear")
     */
    public function crearGuia(Request $request)
    {
        $datos=json_decode($request->getContent(), true);
        $em=$this->getDoctrine()->getManager();

        try{
            $arGuia=(new TteGuia())
                ->setCodigoGuiaPk($datos['codigoGuia']);

            $em->persist($arGuia);
            $em->flush();
        }catch (\Exception $exception){
            return [
                'estado'=>false,
                'mensaje'=>$exception->getMessage(),
            ];
        }

        return [
            'estado'=>true,
        ];
    }
}