<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ApiGuiaController extends FOSRestController
{

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/entrega/{codigoGuia}")
     */
    public function entrega(Request $request, $codigoGuia=0) {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        $fecha = $arrPost['fecha'];
        $hora = $arrPost['hora'];
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiEntrega($codigoGuia, $fecha, $hora);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/soporte/{codigoGuia}")
     */
    public function soporte(Request $request, $codigoGuia=0) {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiSoporte($codigoGuia);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/despacho/adicionar/{codigoDespacho}/{codigoGuia}")
     */
    public function adicionarDespacho(Request $request, $codigoDespacho =0, $codigoGuia=0) {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiDespachoAdicionar($codigoDespacho,$codigoGuia);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/factura/adicionar/{codigoFactura}/{codigoGuia}/{documento}/{tipo}")
     */
    public function adicionarFactura(Request $request, $codigoFactura =0, $codigoGuia=0, $documento="", $tipo=0) {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiFacturaAdicionar($codigoFactura, $codigoGuia, $documento, $tipo);
    }


}
