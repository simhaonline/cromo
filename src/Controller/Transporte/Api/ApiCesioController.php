<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TtePrecio;
use App\Entity\Transporte\TtePrecioDetalle;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiCesioController extends FOSRestController
{
    /**
     * @param Request $request
     * @param string $ciudadOrigen
     * @param string $ciudadDestino
     * @param string $producto
     * @param int $peso
     * @param int $listaPrecio
     * @return JsonResponse
     * @Rest\Get("/transporte/api/cesio/precio/calcular/{ciudadOrigen}/{ciudadDestino}/{producto}/{peso}/{listaPrecio}")
     */
    public function guiaCrear(Request $request, $ciudadOrigen = '', $ciudadDestino = '', $producto = '', $peso = 0, $listaPrecio = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $flete = 0;
        $arPrecioDetalle = $em->getRepository(TtePrecioDetalle::class)->findOneBy(['codigoProductoFk' => $producto, 'codigoCiudadOrigenFk' => $ciudadOrigen, 'codigoCiudadDestinoFk' => $ciudadDestino, 'codigoPrecioFk' => $listaPrecio]);
        if ($arPrecioDetalle) {
            $flete = $arPrecioDetalle->getVrPeso() * $peso;
        }
        return new JsonResponse($flete);
    }

    /**
     * @Rest\Post("/transporte/api/cesio/guia/lista/cliente")
     */
    public function guiaClienteLista(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $raw = json_decode($request->getContent(), true);
        $codigoCliente = $raw['codigoCliente']?? '';
        $fechaDesde = $raw['fechaDesde']?? '';
        $fechaHasta = $raw['fechaHasta']?? '';
        $numero = $raw['numero']?? '';
        $documento = $raw['documento']?? '';
        if($codigoCliente && $fechaDesde && $fechaHasta)  {
            return $arGuias = $em->getRepository(TteGuia::class)->apiCesioListaCliente($codigoCliente,$fechaDesde,$fechaHasta, $numero, $documento);
        } else {
            return [
                'error' => "Faltan datos en el post"
            ];
        }
    }
}