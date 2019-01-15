<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TtePrecio;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiCesioController extends FOSRestController
{
    /**
     * @param Request $request
     * @param $ciudadOrigen
     * @param $ciudadDestino
     * @param $producto
     * @param $peso
     * @param $codigoEmpresa
     * @return float|int
     * @Rest\Get("/transporte/api/cesio/precio/calcular/{ciudadOrigen}/{ciudadDestino}/{producto}/{peso}/{codigoEmpresa}")
     */
    public function crearGuia(Request $request, $ciudadOrigen, $ciudadDestino, $producto, $peso, $codigoEmpresa)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecio = $em->getRepository(TtePrecio::class)->findOneBy(['codigoEmpresaFk' => $codigoEmpresa, 'codigoCiudadOrigenFk' => $ciudadOrigen, 'codigoCiudadDestinoFk' => $ciudadDestino, 'codigoProductoFk' => $producto]);
        if ($arPrecio) {
            $flete = $arPrecio->getVrKilo() * $peso;
        }

        return $flete;
    }
}