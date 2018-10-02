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
     * @Rest\Get("/transporte/api/guia/consulta/{codigoGuia}")
     */
    public function consulta(Request $request, $codigoGuia = 0) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arGuia = $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiConsulta($codigoGuia);
        if($arGuia) {
            return [
                'error' => false,
                'mensaje' => "Guia encontrada",
                'guias' => $arGuia,
            ];
        } else {
            return [
                'error' => true,
                'mensaje' => "No se encontraron guias",
                'guias' => null,
            ];
        }
        //return ;
    }

    /**
     * @param Request $request
     * @param int $codigoDespacho
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Get("/transporte/api/guia/despacho/{codigoDespacho}")
     */
    public function listaDespacho(Request $request, $codigoDespacho = 0) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arGuia = $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiDespacho($codigoDespacho);
        if($arGuia) {
            return [
                'error' => false,
                'mensaje' => "Guia encontrada",
                'guias' => $arGuia,
            ];
        } else {
            return [
                'error' => true,
                'mensaje' => "No se encontraron guias",
                'guias' => null,
            ];
        }
        //return ;
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/entrega/{codigoGuia}/{soporte}")
     */
    public function entrega(Request $request, $codigoGuia = 0, $soporte = "") {

        $arrPost = json_decode($request->request->get("arrParametros"), true);
        $fecha = $arrPost['fecha'];
        $hora = $arrPost['hora'];
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiEntrega($codigoGuia, $fecha, $hora, $soporte);

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
     * @Rest\Post("/transporte/api/guia/despacho/adicionar/documento/{codigoDespacho}/{documento}")
     */
    public function adicionarDespachoDocumento(Request $request, $codigoDespacho = 0, $documento = "") {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiDespachoAdicionarDocumento($codigoDespacho,$documento);
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

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/factura/planilla/adicionar/{codigoFacturaPlanilla}/{codigoGuia}/{documento}/{tipo}")
     */
    public function adicionarFacturaPlanilla(Request $request, $codigoFacturaPlanilla =0, $codigoGuia=0, $documento="", $tipo=0) {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiFacturaPlanillaAdicionar($codigoFacturaPlanilla, $codigoGuia, $documento, $tipo);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/cumplido/adicionar/{codigoCumplido}/{codigoGuia}/{documento}/{tipo}")
     */
    public function adicionarCumplido(Request $request, $codigoCumplido =0, $codigoGuia=0, $documento="", $tipo=0) {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiCumplidoAdicionar($codigoCumplido, $codigoGuia, $documento, $tipo);
    }

}
